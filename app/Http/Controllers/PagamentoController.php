<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;
use App\Models\Mensalidade;
use App\Models\Checkout;
use App\Models\Aluno;

class PagamentoController extends Controller
{
    public function __construct(){
    }

    public function index(){
        $config = Configuracao::first();

        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        foreach($aluno->checkouts as $c){
            if($c->status == "pending"){
                $payStatus = \MercadoPago\Payment::find_by_id($c->transacao_id);
                if(isset($payStatus->status)){
                    if($payStatus->status != $c->status){
                        $c->status = $payStatus->status;
                        $c->save();
                        if($c->status == "approved"){
                            $this->setaMensalidade($c);
                        }
                    }
                }
            }
        }

        $lastCeckout = Checkout::orderBy('id', 'desc')
        ->where('aluno_id', $user['aluno']->id)
        ->first();

        $valor_mensalidade = $aluno->valor_mensalidade;

        if($valor_mensalidade == 0){
            session()->flash("flash_erro", "Mensalidade isenta!");
            return redirect()->back();
        }

        return view('pagamento/pay', compact('config', 'lastCeckout', 'aluno', 'valor_mensalidade'));
    }

    public function pay(Request $request){

        $config = Configuracao::first();

        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        $payment = new \MercadoPago\Payment();

        $payment->transaction_amount = (float)$aluno->valor_mensalidade;
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

            $user = session('user_logged');
            $data = [
                'nome' => $request->nome,
                'sobre_nome' => $request->sobre_nome,
                'email' => $request->email,
                'documento' => $doc,
                'aluno_id' => $user['aluno']->id,
                'valor' => $aluno->valor_mensalidade,
                'transacao_id' => (string)$payment->id,
                'status' => $payment->status,
                'forma_pagamento' => 'pix',
                'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64,
                'qr_code' => $payment->point_of_interaction->transaction_data->qr_code,
            ];
            // die;
            try{
                Checkout::create($data);

                return view('pagamento/qrcode', compact('data'));
            }catch(\Exception $e){
                // echo $e->getMessage();
                // die;
                session()->flash("flash_erro", "Algo deu errado!");
                return redirect()->back();
            }
        }else{
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->back();
        }

    }

    public function status($transacao_id){
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));
        $checkout = Checkout::where('transacao_id', $transacao_id)
        ->first();

        if($checkout){
            $payStatus = \MercadoPago\Payment::find_by_id($checkout->transacao_id);

            if($payStatus->status == "approved" && $checkout->status != $payStatus->status){
                $this->setaMensalidade($checkout);
            }

            $checkout->status = $payStatus->status;
            $checkout->save();

            // return response()->json("approved", 200);
            
            return response()->json($payStatus->status, 200);

        }else{
            return response()->json("erro", 404);
        }
    }

    private function setaMensalidade($checkout){
        $data = [
            'aluno_id' => $checkout->aluno_id,
            'valor' => $checkout->valor,
            'forma_pagamento' => 'pix',
            'observacao' => 'Mercado pago pix: ' . $checkout->transacao_id,
            'data_pagamento' => date('Y-m-d')
        ];

        Mensalidade::create($data);
    }
    
}
