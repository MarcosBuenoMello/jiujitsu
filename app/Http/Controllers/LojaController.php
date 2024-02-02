<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\ProdutoAcesso;
use App\Models\CategoriaProduto;

class LojaController extends Controller
{
    public function produtos(Request $request){
        $data = Produto::
        where('status', 1)
        ->where('estoque', '>', 0)
        ->when(!empty($request->search), function ($q) use ($request) {
            return $q->where(function ($quer) use ($request) {
                return $quer->where('nome', 'LIKE', "%$request->search%");
            });
        })
        ->when(!empty($request->categoria_id), function ($q) use ($request) {
            return $q->where(function ($quer) use ($request) {
                return $quer->where('categoria_id', $request->categoria_id);
            });
        })
        ->when(!empty($request->ordem), function ($q) use ($request) {
            if($request->ordem == 'menor_valor'){
                return $q->orderBy('valor', 'asc');
            }else if($request->ordem == 'maior_valor'){
                return $q->orderBy('valor', 'desc');
            }else{
                return $q->orderBy('nome', 'asc');
            }

        })
        ->when(empty($request->ordem), function ($q) use ($request) {
            return $q->orderBy('id', 'desc');
        })
        ->paginate(15);

        $categorias = CategoriaProduto::all();

        return view('loja/index', compact('data', 'categorias'));
    }

    public function produto_detalhe($id){
        $item = Produto::findOrFail($id);

        $user = session('user_logged');
        ProdutoAcesso::create([
            'produto_id' => $item->id,
            'aluno_id' => $user['aluno']->id
        ]);

        return view('loja/produto_detalhe', compact('item'));
    }

    public function add_item_carrinho(Request $request){

        $item = Produto::findOrFail($request->item_id);
        $user = session('user_logged');
        $aluno = $user['aluno'];

        $soma = $this->somaTotalCarrinho($aluno->id, $item);
        $data = [
            'aluno_id' => $aluno->id,
            'total' => $soma,
            'observacao' => '',
            'qr_code' => '',
            'qr_code_base64' => '',
            'transacao_id' => '',
            'status' =>  '',
            'tipo_pagamento' => 'pix', 
            'carrinho' => true
        ];

        $pedido = Pedido::
        where('aluno_id', $aluno->id)
        ->where('carrinho', true)
        ->first();

        if($pedido == null){
            $pedido = Pedido::create($data);
        }

        $item = [
            'pedido_id' => $pedido->id,
            'produto_id' => $item->id,
            'quantidade' => 1,
            'valor' => $item->valor
        ];
        PedidoItem::create($item);

        session()->flash("flash_sucesso", "Produto adicionado!");
        return redirect('/loja/carrinho');
    }

    public function somaTotalCarrinho($aluno_id, $item){
        $pedido = Pedido::
        where('aluno_id', $aluno_id)
        ->where('carrinho', true)
        ->first();

        if($pedido != null){
            return $pedido->somaItens() + $item->valor;
        }
        return $item->valor;
    }

    public function carrinho(){
        $user = session('user_logged');
        $aluno = $user['aluno'];

        $itensRemovidos = $this->validaItensCarrinho();
        $carrinho = $this->getCarrinho();

        if($carrinho == null){
            return redirect('/loja/produtos');
        }

        return view('loja/carrinho', compact('carrinho', 'itensRemovidos'));
    }

    private function validaItensCarrinho(){
        $carrinho = $this->getCarrinho();
        $auxQtd = [];
        $itensRemovidos = false;
        $soma = 0;
        if($carrinho == null){
            return null;
        }
        foreach($carrinho->itens as $item){
            if(isset($auxQtd[$item->produto_id])){
                $auxQtd[$item->produto_id] += 1;
            }else{
                $auxQtd[$item->produto_id] = 1;
            }

            if($auxQtd[$item->produto_id] > $item->produto->estoque ){
                $item->delete();
                $itensRemovidos = true;
            }else{
                $soma += $item->produto->valor;
            }
        }

        $carrinho->total = $soma;
        $carrinho->save();
        return $itensRemovidos;
    }

    public function delete_item($id){
        $item = PedidoItem::findOrFail($id);
        $item->delete();
        session()->flash("flash_sucesso", "Item removido do carrinho!");
        return redirect()->back();

    }

    public function finalizar(){
        $carrinho = $this->getCarrinho();
        $user = session('user_logged');

        $aluno = Aluno::findOrFail($user['aluno']->id);

        return view('loja/finalizar', compact('carrinho', 'aluno'));
    }

    private function getCarrinho(){
        $user = session('user_logged');
        $aluno = $user['aluno'];

        $carrinho = Pedido::
        where('aluno_id', $aluno->id)
        ->where('carrinho', true)
        ->first();
        return $carrinho;
    }

    public function pagamentoBoleto(Request $request){
        $config = Configuracao::first();
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        $payment = new \MercadoPago\Payment();

        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);

        $carrinho = $this->getCarrinho();

        $payment->transaction_amount = (float)$carrinho->total;
        $payment->description = '';
        $payment->payment_method_id = "bolbradesco";

        $doc = preg_replace('/[^0-9]/', '', $request->docNumber);

        $payment->payer = array(
            "email" => $request->email,
            "first_name" => $request->nome,
            "last_name" => $request->sobre_nome,
            "identification" => array(
                "type" => $request->doc_type,
                "number" => $doc
            ),
            "address"=>  array(
                "zip_code" => getenv("CEP"),
                "street_name" => getenv("RUA"),
                "street_number" => getenv("NUMERO"),
                "neighborhood" => getenv("BAIRRO"),
                "city" => getenv("CIDADE"),
                "federal_unit" => getenv("UF")
            )
        );

        $payment->save();
        if($payment->transaction_details){
            $carrinho->carrinho = false;
            $carrinho->transacao_id = $payment->id;
            $carrinho->status = $payment->status;
            $carrinho->tipo_pagamento = 'boleto';
            $carrinho->link_boleto = $payment->transaction_details->external_resource_url;
            $carrinho->save();

            session()->flash("flash_sucesso", "Pedido finalizado, imprima e pague seu boleto para receber o produto");

            return redirect('loja/pedido_encerreado/'.$payment->id);
        }else{

            session()->flash("flash_erro", "Ocorreu um erro no pagamento: " . $payment->error->message);
            return redirect()->back();
        }
    }

    public function pagamentoPix(Request $request){
        $config = Configuracao::first();
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        $payment = new \MercadoPago\Payment();

        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);

        $carrinho = $this->getCarrinho();

        $payment->transaction_amount = (float)$carrinho->total;
        $payment->description = '';
        $payment->payment_method_id = "pix";

        $doc = preg_replace('/[^0-9]/', '', $request->docNumber);

        $payment->payer = array(
            "email" => $request->email,
            "first_name" => $request->nome,
            "last_name" => $request->sobre_nome,
            "identification" => array(
                "type" => $request->doc_type,
                "number" => $doc
            ),
            "address"=>  array(
                "zip_code" => getenv("CEP"),
                "street_name" => getenv("RUA"),
                "street_number" => getenv("NUMERO"),
                "neighborhood" => getenv("BAIRRO"),
                "city" => getenv("CIDADE"),
                "federal_unit" => getenv("UF")
            )
        );

        $payment->save();
        if($payment->transaction_details){
            $carrinho->carrinho = false;
            $carrinho->transacao_id = $payment->id;
            $carrinho->tipo_pagamento = 'pix';
            $carrinho->qr_code_base64 = $payment->point_of_interaction->transaction_data->qr_code_base64;
            $carrinho->qr_code = $payment->point_of_interaction->transaction_data->qr_code;

            $carrinho->save();

            session()->flash("flash_sucesso", "Pedido finalizado, escaneie o QrCode ou copie a chave para finalizar");

            return redirect('loja/pedido_encerreado/'.$payment->id);
        }else{

            session()->flash("flash_erro", "Ocorreu um erro no pagamento: " . $payment->error->message);
            return redirect()->back();
        }
    }

    public function pagamentoCartao(Request $request){
        $config = Configuracao::first();
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        // \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN"));
        $payment = new \MercadoPago\Payment();

        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);
        $carrinho = $this->getCarrinho();

        $payment->transaction_amount = (float)$carrinho->total;

        $payment->token = $request->token;
        $payment->description = 'Compra de produtos ' . getenv("APP_NAME");
        $payment->payment_method_id = $request->paymentMethodId;
        $payment->installments = (int)$request->installments;
        $payment->token = $request->token;
        $doc = preg_replace('/[^0-9]/', '', $request->docNumber);

        $payer = new \MercadoPago\Payer();
        $payer->email = $request->email;
        $payer->identification = array(
            "type" => $request->doc_type,
            "number" => $request->docNumber
        );
        $payment->payer = $payer;

        // echo "<pre>";
        // print_r($payment);
        // echo "</pre>";
        $payment->save();

        if($payment->error){
            // echo "<pre>";
            // print_r($payment);
            // echo "</pre>";

            // die;
            session()->flash("flash_erro", "Ocorreu um erro no pagamento: " . $payment->error);
            return redirect()->back();
        }else{

            $carrinho->carrinho = false;
            $carrinho->transacao_id = $payment->id;
            $carrinho->status = $payment->status;
            $carrinho->tipo_pagamento = 'cartao';
            $carrinho->save();

            session()->flash("flash_sucesso", "Pedido finalizado pagamento aprovado, obrigado por comprar conosco!");

            return redirect('loja/pedido_encerreado/'.$payment->id);
        }
    }

    public function pedido_encerreado($transacao_id){
        $pedido = Pedido::
        where('transacao_id', $transacao_id)
        ->first();

        return view('loja/pedido_finalizado', compact('pedido'));
    }

    public function status($transacao_id){
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        $pedido = Pedido::where('transacao_id', $transacao_id)
        ->first();

        if($pedido){
            $payStatus = \MercadoPago\Payment::find_by_id($pedido->transacao_id);

            $pedido->status = $payStatus->status;
            $pedido->save();

            // return response()->json("approved", 200);
            
            return response()->json($payStatus->status, 200);

        }else{
            return response()->json("erro", 404);
        }
    }

    public function pedidos(){
        $user = session('user_logged');
        $data = Pedido::
        orderBy('id', 'desc')
        ->where('aluno_id', $user['aluno']->id)
        ->where('carrinho', 0)
        ->get();

        return view('loja/pedidos', compact('data'));
    }

    public function detalhePedido($id){
        $pedido = Pedido::findOrFail($id);
        $user = session('user_logged');

        if($user['aluno']->id != $pedido->aluno_id){
            session()->flash("flash_erro", "Pedido não encontrado");
            return redirect()->back();
        }else{
            return view('loja/detalhes_pedido', compact('pedido'));
        }
    }

}
