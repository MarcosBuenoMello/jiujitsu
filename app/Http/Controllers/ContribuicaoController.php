<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribuicao;
use App\Models\Cidade;
use App\Models\Aluno;
use App\Models\ContribuicaoRetirada;
use App\Models\Configuracao;

class ContribuicaoController extends Controller
{
    public function index(Request $request){

        $this->analisaPagamentos();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $data = Contribuicao::
        orderBy('created_at', 'desc')
        ->select('contribuicaos.*')
        ->join('alunos', 'alunos.id', '=', 'contribuicaos.aluno_id')
        ->when(!empty($request->search), function ($q) use ($request) {
            return $q->where('alunos.nome', 'LIKE', "%$request->search%");
        })
        ->when(!empty($request->cidade_id), function ($q) use ($request) {
            return $q->where('alunos.cidade_id', $request->cidade_id);
        })
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('contribuicaos.created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('contribuicaos.created_at', '<=', $end_date);
        })
        ->where('contribuicaos.status', 'approved')
        ->paginate(getenv("PAGINATE"));

        $cidades = Cidade::all();

        $sumRetirada = ContribuicaoRetirada::sum('valor');

        $somaTotal = $this->somaTotal();
        return view('contribuicao/index', compact('data', 'cidades', 'somaTotal', 'sumRetirada'));
    }

    private function analisaPagamentos(){
        $value = session('user_logged');

        $config = Configuracao::first();
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_CONTRIB"));

        if($value['master']){
            $data = Contribuicao::
            orderBy('id', 'desc')
            ->where('status', 'pendding')
            ->limit(50)
            ->get();

            foreach($data as $item){
                $payStatus = \MercadoPago\Payment::find_by_id($item->transacao_id);

                if($payStatus){
                    $item->status = $payStatus->status;
                    $item->save();
                }
            }
        }

    }

    public function create(){
        $value = session('user_logged');
        $aluno = Aluno::findOrFail($value['aluno']->id);
        $ultimaContrib = Contribuicao::
        where('aluno_id', $aluno->id)
        ->orderBy('created_at', 'desc')
        ->where('status', 'approved')
        ->first();

        $config = Configuracao::first();

        $valor_contribuicao = $config->valor_contribuicao;

        $mesDeContrib = '';
        $mesUltimaContrib = null;
        if($ultimaContrib != null){
            
            $mesUltimaContrib = \Carbon\Carbon::parse($ultimaContrib->created_at)->format('m');
            $mesAtual = date('m');

            $mesUltimaContrib = (int)$mesUltimaContrib;
            if($mesUltimaContrib == 12) $mesUltimaContrib = 0;
            $mesDeContrib = (int)$mesUltimaContrib;
            $mesDeContrib = $this->getMesContrib($mesDeContrib);
            
        }

        if($mesUltimaContrib != null && $mesUltimaContrib == date('m')){
            session()->flash("flash_erro", "Você já fez a contribuição este mês!");
            return redirect()->back();
        }

        return view('contribuicao/create', compact('aluno', 'ultimaContrib', 'valor_contribuicao', 
            'mesDeContrib'));

    }

    private function getMesContrib($indice){
        $meses = [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];
        return $meses[$indice];
    }

    public function store(Request $request){
        $config = Configuracao::first();
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_CONTRIB"));
        $payment = new \MercadoPago\Payment();

        $payment->transaction_amount = (float)$config->valor_contribuicao;
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
                'email' => $request->email,
                'cpf' => $doc,
                'aluno_id' => $user['aluno']->id,
                'valor' => $config->valor_contribuicao,
                'transacao_id' => (string)$payment->id,
                'status' => $payment->status,
                'forma_pagamento' => 'pix',
                'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64,
                'qr_code' => $payment->point_of_interaction->transaction_data->qr_code,
            ];

            try{
                Contribuicao::create($data);

                return view('contribuicao/qrcode', compact('data'));
            }catch(\Exception $e){
                echo $e->getMessage();
                die;
                session()->flash("flash_erro", "Algo deu errado!");
                return redirect()->back();
            }
        }else{
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->back();
        }
    }

    public function status($transacao_id){
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_CONTRIB"));
        $contrib = Contribuicao::where('transacao_id', $transacao_id)
        ->first();

        if($contrib){
            $payStatus = \MercadoPago\Payment::find_by_id($contrib->transacao_id);

            // $payStatus->status = "approved";

            if($payStatus->status == "approved" && $contrib->status != $payStatus->status){
                $contrib->status = $payStatus->status;
                $date = $this->getLastContrib($contrib->aluno);
                $contrib->created_at = $date;
                $contrib->updated_at = $date;

            // $contrib->status = "approved";
                $contrib->save();
            }
            
            // return response()->json("approved", 200);
            
            return response()->json($payStatus->status, 200);

        }else{
            return response()->json("erro", 404);
        }
    }

    private function getLastContrib($aluno){
        $ultimaContrib = Contribuicao::
        where('aluno_id', $aluno->id)
        ->orderBy('id', 'desc')
        ->where('status', 'approved')
        ->first();
        
        if($ultimaContrib == null){
            return date('Y-m-d H:i:s');
        }

        $mesUltimaContrib = \Carbon\Carbon::parse($ultimaContrib->created_at)->format('m');
        $anoUltimaContrib = \Carbon\Carbon::parse($ultimaContrib->created_at)->format('Y');
        $year = date('Y');

        $mesUltimaContrib = (int)$mesUltimaContrib+1;

        if($anoUltimaContrib != $year){
            if($mesUltimaContrib > 12){

            }else{
                $year = $anoUltimaContrib;
            }
        }

        $mesUltimaContrib = $mesUltimaContrib > 12 ? 1 : $mesUltimaContrib;
        $mesUltimaContrib = $mesUltimaContrib < 10 ? "0".$mesUltimaContrib : $mesUltimaContrib;


        return $year . "-$mesUltimaContrib-".date('d') . " " . date('H:i:s'); 

    }

    private function somaTotal(){
        $total = Contribuicao::
        where('status', 'approved')
        ->sum('valor');
        return $total;
    }

    public function retirar(){
        $data = ContribuicaoRetirada::orderBy('id', 'desc')->get();
        return view('contribuicao/retirar', compact('data'));
    }

    public function retirarStore(Request $request){
        $this->_validate($request);
        try{
            $request->merge(['valor' => __replace($request->valor)]);
            ContribuicaoRetirada::create($request->all());

            session()->flash("flash_sucesso", "Registro salvo!");

        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->back();

    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'valor' => 'required',
            'motivo' => 'required',
        ];

        $messages = [
            'valor.required' => 'Valor é obrigatório.',
            'motivo.required' => 'Motivo é obrigatório.',

        ];
        $this->validate($request, $rules, $messages);
    }

    public function contribuicaoRetirarDelete($id){
        try{
            $item = ContribuicaoRetirada::findOrFail($id);
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido!");

        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->back();
    }

    public function pendentes(){
        $alunos = Aluno::orderBy('nome', 'asc')->get();
        $pendentes = [];

        foreach($alunos as $a){
            $contrib = Contribuicao::
            where('aluno_id', $a->id)
            ->where('status', "approved")
            ->whereMonth('created_at', '=', date('m'))
            ->whereYear('created_at', '=', date('Y'))
            ->first();

            if($contrib == null){

                $lastContrib = Contribuicao::
                where('aluno_id', $a->id)
                ->where('status', "approved")
                ->first();

                $a->data_ultimo_pagamento = "";
                if($lastContrib != null){
                    $a->data_ultimo_pagamento = $lastContrib->created_at;
                }
                array_push($pendentes, $a);
            }
        }

        return view('contribuicao/pendentes', compact('pendentes'));
    }

    public function consulta(){
        $modificado = [];
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_CONTRIB"));
        $data = Contribuicao::limit(200)
        ->orderBy('created_at', 'desc')
        ->get();

        foreach($data as $c){
            $payStatus = \MercadoPago\Payment::find_by_id($c->transacao_id);
            if($payStatus){
                if($c->status != $payStatus->status){

                    $c->status = $payStatus->status;
                    $c->save();

                    array_push($modificado, $c);

                }
            }else{
                $c->status = "cancelled";
                $c->save();
            }
        }

        return view('contribuicao/consulta', compact('modificado'));
    }

    public function updateDate(Request $request){
        $contribuicao = Contribuicao::findOrFail($request->contribuicao_id);
        try{
            $novaData = $request->data;
            $novaData = $novaData . " " . 
            \Carbon\Carbon::parse($contribuicao->created_at)->format('H:i:s');

            $contribuicao->created_at = $novaData;
            $contribuicao->updated_at = $novaData;
            $contribuicao->save();
            session()->flash("flash_sucesso", "Data alterada!");

        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado!");
        }
        return redirect()->back();
    }
}
