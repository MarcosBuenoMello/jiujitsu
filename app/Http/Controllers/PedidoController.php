<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Aluno;
use App\Models\Cidade;

class PedidoController extends Controller
{
    public function index(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $data = Pedido::
        select('pedidos.*')
        ->orderBy('id', 'desc')
        ->join('alunos', 'alunos.id', '=', 'pedidos.aluno_id')
        ->when(!empty($request->search), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('nome', 'LIKE', "%$request->search%");
            });
        })
        ->when(!empty($request->status), function ($q) use ($request) {
            return $q->where(function ($quer) use ($request) {
                return $quer->where('pedidos.status', $request->status);
            });
        })
        ->when(!empty($request->cidade_id), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('alunos.cidade_id', $request->cidade_id);
            });
        })
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('pedidos.updated_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('pedidos.updated_at', '<=', $end_date);
        })
        ->where('carrinho', 0)
        ->paginate(getenv("PAGINATE"));

        $alunos = Aluno::orderBy('nome', 'desc')->get();
        $cidades = Cidade::all();

        return view('pedidos/index', compact('data', 'alunos', 'cidades'));
    }

    public function destroy($id){
        $item = Pedido::findOrFail($id);

        try {
            $item->itens()->delete();
            $item->delete();

            session()->flash("flash_sucesso", "pedido removido");

        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
        }
        return redirect()->back();

    }

    public function consultaPagamentos(){
        $pedidos = Pedido::all();

        $alteracoes = [];
        \MercadoPago\SDK::setAccessToken(getenv("MERCADOPAGO_ACCESS_TOKEN_PRODUCAO"));

        foreach($pedidos as $p){
            if($p->transacao_id != ""){
                $payStatus = \MercadoPago\Payment::find_by_id($p->transacao_id);
                if($payStatus){
                    if($p->status != $payStatus->status){
                        $p->status = $payStatus->status;
                        $p->save();

                        $push = [
                            'status' => $p->status,
                            'pedido_id' => $p->id,
                            'transacao_id' => $p->transacao_id,
                            'aluno' => $p->aluno->full_name,
                            'total' => $p->total,
                            'data' => $p->created_at,
                        ];
                        array_push($alteracoes, $push);
                    }
                }
            }
        }
        if(sizeof($alteracoes) > 0){
            return view('pedidos/alteracoes_pagamento', compact('alteracoes'));
        }else{

            return redirect('pedidos');
        }
    }

    public function show($id){
        $pedido = Pedido::findOrFail($id);
        return view('pedidos/show', compact('pedido'));
    }

    public function carrinhos(){
        $data = Pedido::
        orderBy('id', 'desc')
        ->where('carrinho', 1)
        ->paginate(getenv("PAGINATE"));

        $alunos = Aluno::orderBy('nome', 'desc')->get();
        $cidades = Cidade::all();

        return view('pedidos/index', compact('data', 'alunos', 'cidades'));
    }
}
