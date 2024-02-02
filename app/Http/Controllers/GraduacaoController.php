<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlunoGraduacao;
use App\Models\Aluno;
use App\Models\Faixa;
use Illuminate\Support\Facades\DB;

class GraduacaoController extends Controller
{
    public function index(Request $request){
        $data = AlunoGraduacao::
        select('aluno_graduacaos.*')
        ->orderBy('data', 'desc')

        ->when(!empty($request->faixa_id), function ($q) use ($request) {
            return $q->where('faixa_id', $request->faixa_id);
        })
        ->when(!empty($request->search), function ($q) use ($request) {
            return $q->join('alunos', 'alunos.id', '=', 'aluno_graduacaos.aluno_id')
            ->where('alunos.nome', 'LIKE', "%$request->search%");

        })
        ->paginate(getenv("PAGINATE"));

        $faixas = Faixa::all();

        return view('graduacao/index', compact('data', 'faixas'));
    }

    public function new(){
        $alunos = Aluno::orderBy('nome', 'asc')->get();
        $faixas = Faixa::all();

        return view('graduacao/create', compact('alunos', 'faixas'));
    }


    public function delete($id){
        $item = AlunoGraduacao::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/graduacao');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/graduacao');
        }

    }

    public function store(Request $request){
        $this->_validate($request);

        try{
            DB::transaction(function () use ($request) {

                $inputs = $request->all();
                AlunoGraduacao::create($inputs);

            });
            session()->flash("flash_sucesso", "Gradução registrada!");
            return redirect('/graduacao');
        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/graduacao');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'aluno_id' => 'required',
            'faixa_id' => 'required',
            'grau' => 'required',
            'data' => 'required',
        ];

        $messages = [
            'aluno_id.required' => 'Aluno é obrigatório.',
            'faixa_id.required' => 'Faixa é obrigatório.',
            'data.required' => 'Data é obrigatório.',
            'grau.required' => 'Grau é obrigatório.',

        ];
        $this->validate($request, $rules, $messages);
    }
}
