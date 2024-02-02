<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faixa;
use App\Models\Posicao;
use App\Models\Aluno;
use App\Models\ExameFaixa;
use App\Models\ExameFaixaPosicao;
use App\Models\AlunoExamePosicao;
use App\Models\AlunoExame;

class ExameController extends Controller
{
    public function index(Request $request){
        $exames = ExameFaixa::orderBy('descricao', 'desc')
        ->when(!empty($request->faixa_id), function ($q) use ($request) {

            return $q->where('exame_faixas.faixa_id', $request->faixa_id);
        })
        ->paginate(getenv("PAGINATE"));
        $faixas = Faixa::all();

        return view('exames/index', compact('exames', 'faixas'));
    }

    public function new(){
        $faixas = Faixa::all();
        $posicoes = Posicao::all();

        return view('exames/create', compact('posicoes', 'faixas'));
    }

    public function edit($id){
        $faixas = Faixa::all();
        $posicoes = Posicao::all();
        $item = ExameFaixa::findOrFail($id);

        foreach($item->posicoes as $i){
            $i->posicao_nome = $i->posicao->nome;
        }
        return view('exames/edit', compact('posicoes', 'faixas', 'item'));
    }

    public function store(Request $request){
        $this->_validate($request);
        try{
            $exame = ExameFaixa::create([
                'faixa_id' => $request->faixa_id,
                'descricao' => $request->descricao,
            ]);

            $posicoes = json_decode($request->posicoes);
            foreach($posicoes as $p){
                ExameFaixaPosicao::create([
                    'posicao_id' => $p->posicao_id,
                    'exame_id' => $exame->id
                ]);
            }

            session()->flash('mensagem_sucesso', 'Exame registrado!');
            return redirect()->route('exame.index');
        }catch(\Exception $e){
            session()->flash('mensagem_erro', 'Algo deu errado!');
            return redirect()->route('exame.index');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'posicoes' => 'required',
            'descricao' => 'required|max:255',
            'faixa_id' => 'required',
        ];

        $messages = [
            'posicoes.required' => 'Adicione as posições.',
            'descricao.required' => 'Descrição é obrigatória.',
            'descricao.max' => '255 caracteres permitidos.',
            'faixa_id.required' => 'Faixa é obrigatória.',
        ];
        $this->validate($request, $rules, $messages);
    }

    public function delete($id){
        $item = ExameFaixa::findOrFail($id);

        try {

            $item->posicoes()->delete();
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido!");

            return redirect('/exames');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/exames');
        }

    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = ExameFaixa::findOrFail($id);

        try{

            $item->fill($request->all())->save();
            $item->posicoes()->delete();
            $posicoes = json_decode($request->posicoes);

            foreach($posicoes as $p){
                ExameFaixaPosicao::create([
                    'posicao_id' => $p->posicao_id,
                    'exame_id' => $item->id
                ]);
            }
            session()->flash("exames", "Exame editado!");
            return redirect('/exames');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/exames');
        }
    }

    public function view($id){
        $item = ExameFaixa::findOrFail($id);
        $alunos = Aluno::orderBy('nome', 'asc')->get();
        return view('exames/view', compact('item', 'alunos'));
    }

    public function includeAluno(Request $request, $id){
        $item = ExameFaixa::findOrFail($id);

        $isCad = AlunoExame::where('aluno_id', $request->aluno_id)
        ->where('exame_id', $id)
        ->first();

        if($isCad){
            session()->flash("flash_erro", "Este exame já esta atribuido a este aluno(a)!");
            return redirect()->back();
        }

        try{
            $alunoExame = AlunoExame::create([
                'aluno_id' => $request->aluno_id,
                'exame_id' => $item->id, 
                'observacao' => $request->observacao ?? ''
            ]);

            foreach($item->posicoes as $p){

                AlunoExamePosicao::create([
                    'aluno_exame_id' => $alunoExame->id,
                    'posicao_id' => $p->posicao_id,
                    'status' => false
                ]);
            }

            $aluno = Aluno::findOrFail($request->aluno_id);
            session()->flash("flash_sucesso", "Exame atribuido ao aluno $aluno->nome!");


        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
        }
        return redirect()->back();

    }
}
