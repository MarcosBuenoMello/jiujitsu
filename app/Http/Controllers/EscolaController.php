<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escola;
use App\Models\Aluno;
use Illuminate\Support\Facades\DB;

class EscolaController extends Controller
{
    public function index(){
        $data = Escola::orderBy('nome')->get();

        return view('escolas.index', compact('data'));
    }

    public function create(){
        return view('escolas.create');
    }

    public function edit($id){
        $item = Escola::findOrFail($id);
        return view('escolas/edit', compact('item'));
    }

    public function store(Request $request){
        $this->_validate($request);

        try{
            DB::transaction(function () use ($request) {

                $inputs = $request->all();
                Escola::create($inputs);

            });
            session()->flash("flash_sucesso", "Escola registrada!");
            return redirect('/escolas');
        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/escolas');
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = Escola::findOrFail($id);

        try{
            DB::transaction(function () use ($request, $item) {

                $item->fill($request->all())->save();

            });
            session()->flash("flash_sucesso", "Escola editada!");
            return redirect('/escolas');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/escolas');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'nome' => 'required|max:50'
        ];

        $messages = [
            'nome.required' => 'Nome é obrigatório.',
            'nome.max' => '50 caracteres permitidos.'
        ];
        $this->validate($request, $rules, $messages);
    }

    public function destroy($id){
        $item = Escola::findOrFail($id);

        try{
            $item->delete();
            session()->flash("flash_sucesso", "Escola removida!");
            return redirect('/escolas');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/escolas');
        }
    }

    public function listAlunos(Request $request){
        $data = Aluno::where('escola_id', '!=', null)
        ->when(!empty($request->search), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('nome', 'LIKE', "%$request->search%");
            });
        })
        ->orderBy('nome', 'asc')->get();
        return view('escolas/alunos_list', compact('data'));

    }
}
