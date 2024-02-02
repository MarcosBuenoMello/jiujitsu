<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaProduto;
use Illuminate\Support\Facades\DB;

class CategoriaProdutoController extends Controller
{
    public function index(Request $request){

        $categorias = CategoriaProduto::orderBy('nome', 'desc')
        ->paginate(getenv("PAGINATE"));

        return view('categoria-produtos/index', compact('categorias'));
    }

    public function create(){
        return view('categoria-produtos/create');
    }

    public function edit($id){
        $item = CategoriaProduto::findOrFail($id);
        return view('categoria-produtos/edit', compact('item'));
    }

    public function destroy($id){
        $item = CategoriaProduto::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect()->route('categoria-produtos.index');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->route('categoria-produtos.index');
        }

    }

    public function store(Request $request){
        $this->_validate($request);

        try{
            DB::transaction(function () use ($request) {

                $inputs = $request->all();
                CategoriaProduto::create($inputs);

            });
            session()->flash("flash_sucesso", "Categoria registrada!");
            return redirect()->route('categoria-produtos.index');
        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->route('categoria-produtos.index');
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = CategoriaProduto::findOrFail($id);

        try{
            DB::transaction(function () use ($request, $item) {

                $item->fill($request->all())->save();

            });
            session()->flash("flash_sucesso", "Categoria editada!");
            return redirect('/categoria-produtos');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/categoria-produtos');
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
