<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\Modalidade;
use App\Models\Cidade;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function index(Request $request){

        $agenda = Agenda::
        select('agendas.*')
        ->when(!empty($request->modalidade_id), function ($q) use ($request) {
            return $q->where('modalidade_id', $request->modalidade_id);
        })
        ->when(!empty($request->sexo) && $request->sexo != 't', function ($q) use ($request) {
            return $q->where('sexo', $request->sexo);
        })
        ->when(!empty($request->cidade_id), function ($q) use ($request) {
            return $q->where('cidade_id', $request->cidade_id);
        })
        ->when(!empty($request->status), function ($q) use ($request) {
            return $q->where('status', $request->status);
        })
        ->get();

        $modalidades = Modalidade::all();
        $cidades = Cidade::all();

        return view('agenda/index', compact('agenda', 'modalidades', 'cidades'));
    }

    public function new(){
        $modalidades = Modalidade::all();
        $cidades = Cidade::all();
        return view('agenda/create', compact('modalidades', 'cidades'));
    }

    public function edit($id){
        $item = Agenda::findOrFail($id);
        $modalidades = Modalidade::all();
        $cidades = Cidade::all();
        return view('agenda/edit', compact('modalidades', 'cidades', 'item'));
    }

    public function store(Request $request){
        $this->_validate($request);
        try{
            DB::transaction(function () use ($request) {


                Agenda::create($request->all());
                
            });
            session()->flash("flash_sucesso", "Treino registrado!");
            return redirect('/agenda');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/agenda');
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request);
        $item = Agenda::findOrFail($id);

        try{
            DB::transaction(function () use ($request, $item) {

                $item->fill($request->all())->save();
                
            });
            session()->flash("flash_sucesso", "Treino editado!");
            return redirect('/agenda');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/agenda');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'horario' => 'required|min:5|max:5',
        ];

        $messages = [
            'horario.required' => 'Horário é obrigatório.',
            'horario.min' => 'Horário incorreto.',
            'horario.max' => 'Horário incorreto.',

        ];
        $this->validate($request, $rules, $messages);
    }

    public function delete($id){
        $item = Agenda::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/agenda');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/agenda');
        }

    }

}
