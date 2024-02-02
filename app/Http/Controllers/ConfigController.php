<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;

class ConfigController extends Controller
{
    public function index(){
        $item = Configuracao::first();

        return view('configuracao/index', compact('item'));
    }

    public function store(Request $request){
        $this->_validate($request);
        $config = Configuracao::first();
        $request->merge([
            'valor_mensalidade' => __replace($request->valor_mensalidade),
            'valor_contribuicao' => __replace($request->valor_contribuicao),
        ]);

        if($config == null){
            Configuracao::create($request->all());
            session()->flash("flash_sucesso", "Configuração criada!");

        }else{
            $config->fill($request->all())->save();
            session()->flash("flash_sucesso", "Configuração editada!");
        }
        return redirect('/config');
    }

    private function _validate(Request $request){

        $rules = [
            'valor_mensalidade' => 'required',
            'dias_para_bloqueio' => 'required',
            'dia_pagamento' => 'required',
            'minutos_para_presenca' => 'required',
        ];

        $messages = [
            'valor_mensalidade.required' => 'Valor é obrigatório.',
            'dias_para_bloqueio.required' => 'Dias é obrigatório.',
            'dia_pagamento.required' => 'Dia de pagamento é obrigatório.',
            'minutos_para_presenca.required' => 'Minutos presenca é obrigatório.',
        ];
        $this->validate($request, $rules, $messages);
    }
}
