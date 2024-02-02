<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index(Request $request){

        $categorias = Categoria::orderBy('nome', 'desc')
        ->paginate(getenv("PAGINATE"));

        return view('categorias/index', compact('categorias'));
    }

    public function new(){
        return view('categorias/create');
    }

    public function edit($id){
        $item = Categoria::findOrFail($id);
        return view('categorias/edit', compact('item'));
    }

    public function delete($id){
        $item = Categoria::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/categorias');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/categorias');
        }

    }

    public function store(Request $request){
        $this->_validate($request);

        try{
            DB::transaction(function () use ($request) {

                $inputs = $request->all();
                Categoria::create($inputs);

            });
            session()->flash("flash_sucesso", "Categoria registrada!");
            return redirect('/categorias');
        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/categorias');
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = Categoria::findOrFail($id);

        try{
            DB::transaction(function () use ($request, $item) {

                $item->fill($request->all())->save();

            });
            session()->flash("flash_sucesso", "Categoria editada!");
            return redirect('/categorias');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/categorias');
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
}
