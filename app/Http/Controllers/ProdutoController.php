<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\CategoriaProduto;
use App\Models\ProdutoGaleria;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(Request $request){

        $categorias = CategoriaProduto::orderBy('nome', 'desc')->get();
        $data = Produto::orderBy('nome', 'desc')
        ->when(!empty($request->search), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('nome', 'LIKE', "%$request->search%");
            });
        })
        ->when(!empty($request->categoria_id), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('categoria_id', $request->categoria_id);
            });
        })
        ->when(!empty($request->status), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                $status = $request->status == -1 ? 0 : $request->status;
                return $quer->where('status', $status);
            });
        })
        ->paginate(getenv("PAGINATE"));

        return view('produtos/index', compact('categorias', 'data'));
    }

    public function create(){
        $categorias = CategoriaProduto::orderBy('nome', 'desc')->get();
        return view('produtos/create', compact('categorias'));
    }

    public function edit($id){
        $item = Produto::findOrFail($id);
        $categorias = CategoriaProduto::orderBy('nome', 'desc')->get();

        return view('produtos/edit', compact('item', 'categorias'));
    }

    public function destroy($id){
        $item = Produto::findOrFail($id);

        try {

            foreach($item->galeria as $galeria){
                $path = public_path('produtos_image/') . $galeria->imagem;
                if(file_exists($path)){
                    unlink($path);
                }
                $galeria->delete();
            }
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect()->route('produtos.index');
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado: Este produto foi vendido não é possível remove-lo");
            return redirect()->route('produtos.index');
        }

    }

    public function store(Request $request){
        $this->_validate($request);

        if(!is_dir(public_path('produtos_image'))){
            mkdir(public_path('produtos_image'), 0777, true);
        }
        
        try{
            DB::transaction(function () use ($request) {

                $request->merge([
                    'valor' => __replace($request->valor),
                    'descricao' => $request->descricao ?? '',
                    'tamanho' => $request->tamanho ?? ''
                ]);
                // $inputs = $request->all();
                $p = Produto::create($request->all());

                if($request->hasFile('file')){
                    $file = $request->file('file');

                    $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
                    $file->move(public_path('produtos_image'), $fileName);

                    ProdutoGaleria::create([
                        'produto_id' => $p->id, 
                        'imagem' => $fileName

                    ]);

                }

            });
            session()->flash("flash_sucesso", "Produto cadastrado!");
            return redirect()->route('produtos.index');
        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado: " . $e->getMessage());
            return redirect()->route('produtos.index');
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = Produto::findOrFail($id);

        try{
            DB::transaction(function () use ($request, $item) {
                $request->merge([
                    'valor' => __replace($request->valor),
                    'descricao' => $request->descricao ?? '',
                    'tamanho' => $request->tamanho ?? ''
                ]);
                $item->fill($request->all())->save();

                if($request->hasFile('file')){
                    $file = $request->file('file');

                    $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
                    $file->move(public_path('produtos_image'), $fileName);

                    if(sizeof($item->galeria) > 0){
                        $g = $item->galeria[0];
                        $path = public_path('produtos_image/'). $g->imagem;
                        if(file_exists($path)){
                            unlink($path);
                            $g->imagem = $fileName;
                            $g->save();
                        }
                    }else{
                        ProdutoGaleria::create([
                            'produto_id' => $item->id, 
                            'imagem' => $fileName
                        ]);
                    }

                }

            });
            session()->flash("flash_sucesso", "Produto editado!");
            return redirect()->route('produtos.index');

        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->route('produtos.index');

        }
    }

    public function upload_image(Request $request, $id){
        $item = Produto::findOrFail($id);

        if($request->hasFile('file')){
            $file = $request->file('file');

            $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('produtos_image'), $fileName);

            
            ProdutoGaleria::create([
                'produto_id' => $item->id, 
                'imagem' => $fileName
            ]);
            

            session()->flash("flash_sucesso", "Imagem adicionada!");
            return redirect()->back();

        }else{
            session()->flash("flash_erro", "Selecione uma imagem!");
            return redirect()->back();
        }
    }

    public function destroy_image($id){
        $item = ProdutoGaleria::findOrFail($id);

        try {


            $path = public_path('produtos_image/') . $item->imagem;
            if(file_exists($path)){
                unlink($path);
            }

            $item->delete();
            session()->flash("flash_sucesso", "Imagem removida");

            return redirect()->back();
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->back();

        }

    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'nome' => 'required|max:50',
            'valor' => 'required',
            'categoria_id' => 'required',
            // 'tamanho' => 'required',
        ];

        $messages = [
            'nome.required' => 'Nome é obrigatório.',
            'nome.max' => '50 caracteres permitidos.',
            'valor.required' => 'Valor é obrigatório.',
            'categoria_id.required' => 'Categoria é obrigatória.',

        ];
        $this->validate($request, $rules, $messages);
    }

    public function galery($id){
        $item = Produto::findOrFail($id);
        return view('produtos/galery', compact('item'));
    }
}
