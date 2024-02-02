<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensalidade;
use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\Cidade;
use App\Models\Checkout;

class MensalidadeController extends Controller
{

    public function index(Request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $data = Mensalidade::orderBy('id', 'desc')
        ->select('mensalidades.*')
        ->when(!empty($request->search), function ($q) use ($request) {
            return $q->join('alunos', 'alunos.id', '=', 'mensalidades.aluno_id')
            ->where('alunos.nome', 'LIKE', "%$request->search%");
        })
        ->when(!empty($request->cidade_id), function ($q) use ($request) {
            return $q->join('alunos', 'alunos.id', '=', 'mensalidades.aluno_id')
            ->where('alunos.cidade_id', $request->cidade_id);
        })
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('mensalidades.data_pagamento', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('mensalidades.data_pagamento', '<=', $end_date);
        })
        ->paginate(getenv("PAGINATE"));

        $somaMes = $this->totalMesAtual();
        $somaFiltro = $this->somaFiltro($request);

        $cidades = Cidade::all();
        return view('mensalidade/index', compact('data', 'cidades', 'somaMes', 'somaFiltro'));
    }

    private function totalMesAtual(){
        $mes = date('m');
        $ano = date('Y');

        $somaMes = Mensalidade::
        select(\DB::raw("SUM(valor) as soma"))
        ->whereMonth('data_pagamento', $mes)
        ->whereYear('data_pagamento', $ano)
        ->first();

        return $somaMes->soma != null ? $somaMes->soma : 0;
    }

    private function somaFiltro($request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $data = Mensalidade::orderBy('id', 'desc')
        ->select('mensalidades.*')
        ->when(!empty($request->search), function ($q) use ($request) {
            return $q->join('alunos', 'alunos.id', '=', 'mensalidades.aluno_id')
            ->where('alunos.nome', 'LIKE', "%$request->search%");
        })
        ->when(!empty($request->cidade_id), function ($q) use ($request) {
            return $q->join('alunos', 'alunos.id', '=', 'mensalidades.aluno_id')
            ->where('alunos.cidade_id', $request->cidade_id);
        })
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('mensalidades.data_pagamento', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('mensalidades.data_pagamento', '<=', $end_date);
        })
        ->get();
        $total = 0;

        foreach($data as $item){
            $total += $item->valor;
        }
        return $total;
    }

    public function new(){
        $alunos = Aluno::orderBy('nome', 'asc')
        ->get();

        $config = Configuracao::first();

        return view('mensalidade/create', compact('alunos', 'config'));
    }

    public function store(Request $request){
        $this->_validate($request);
        try{
            $request->merge(
                [
                    'valor' => __replace($request->valor),
                    'observacao' => $request->observacao ?? ''
                ]
            );

            Mensalidade::create($request->all());
            session()->flash('flash_sucesso', 'Pagamento cadastrado!');
            return redirect('/mensalidade');

        }catch(\Exception $e){

            session()->flash('flash_erro', 'Algo deu errado!');
            return redirect('/mensalidade');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'aluno_id' => 'required',
            'forma_pagamento' => 'required',
            'valor' => 'required',
            'data_pagamento' => 'required',
        ];

        $messages = [
            'aluno_id.required' => 'Aluno é obrigatório.',
            'forma_pagamento.required' => 'Forma de pag. é obrigatório.',
            'data_pagamento.required' => 'Data de pag. é obrigatório.',
            'valor.required' => 'Valor é obrigatório.',

        ];
        $this->validate($request, $rules, $messages);
    }

    public function delete($id){
        $item = Mensalidade::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/mensalidade');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/mensalidade');
        }
    }

    public function pendentes(){
        $alunos = Aluno::orderBy('nome', 'asc')->get();
        $pendentes = [];
        $config = Configuracao::first();

        foreach($alunos as $a){

            if(!__insMaster($a->email)){

                $pag = Mensalidade::
                whereMonth('data_pagamento', '=', date('m'))
                ->whereYear('data_pagamento', '=', date('Y'))
                ->where('aluno_id', $a->id)
                ->first();


                if($pag == null){
                    $ult = Mensalidade::
                    where('aluno_id', $a->id)
                    ->orderBy('id', 'desc')
                    ->first();

                    $a->data_ultimo_pagamento = $ult != null ? $ult->data_pagamento : '';

                    if($config->dia_pagamento <= date('d'))
                        array_push($pendentes, $a);
                }
            }
        }

        return view('mensalidade/pendentes', compact('pendentes'));
    }

    public function consultaPIX(){
        $checkouts = Checkout::where('status', '!=', 'approved')
        ->limit(30)
        ->orderBy('created_at', 'desc')
        ->get();

        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));

        $modificado = [];

        foreach($checkouts as $c){
            $payStatus = \MercadoPago\Payment::find_by_id($c->transacao_id);
            if($payStatus){
                if($c->status != $payStatus->status){
                    $mensalalidade = Mensalidade::
                    where('observacao', 'Mercado pago pix: '.$c->transacao_id)
                    ->first();

                    $c->status = $payStatus->status;
                    if($mensalalidade == null){
                        array_push($modificado, $c);
                    }
                }
            }
        }

        return view('mensalidade/consulta_pix', compact('modificado'));
    }

    public function setPlano($id){
        $checkout = Checkout::findOrFail($id);   
        $this->setaMensalidade($checkout);
        $checkout->status = "approved";
        $checkout->save();
        session()->flash("flash_sucesso", "Mensalidade atribuida");

        return redirect()->back();
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

    public function deleteCheckout($id){
        $item = Checkout::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect()->back();
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->back();
        }
    }
}
