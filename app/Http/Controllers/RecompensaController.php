<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recompensa;
use App\Models\Faixa;
use App\Models\Aluno;
use Illuminate\Support\Facades\DB;

class RecompensaController extends Controller
{
    public function index(){
        $data = Recompensa::
        select('recompensas.*')
        ->get();

        return view('recompensas/index', compact('data'));
    }

    public function new(){
        $faixas = Faixa::all();

        return view('recompensas/create', compact('faixas'));
    }

    public function edit($id){
        $item = Recompensa::findOrFail($id);
        $faixas = Faixa::all();

        return view('recompensas/edit', compact('faixas', 'item'));
    }

    public function store(Request $request){
        $this->_validate($request);
        try{
            DB::transaction(function () use ($request) {

                Recompensa::create($request->all());
            });
            session()->flash('flash_sucesso', "Salvo com sucesso!");
            return redirect()->route('recompensa.index');
        }catch(\Exception $e){
            session()->flash('flash_erro', "Algo deu errado " . $e->getMessage());
            return redirect()->route('recompensa.index');
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = Recompensa::findOrFail($id);

        try{
            DB::transaction(function () use ($request, $item) {

                $item->fill($request->all())->save();

            });
            session()->flash("flash_sucesso", "Recompensa editada!");
            return redirect('/recompensas');
        }catch(\Exception $e){
            echo $e->getMessage();
            die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/recompensas');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'grau' => 'required|max:1',
            'total_presencas' => 'required',
            'descricao' => 'required|max:255',
        ];

        $messages = [
            'grau.required' => 'Grau é obrigatório.',
            'total_presencas.required' => 'Total de presença é obrigatório.',
            'descricao.required' => 'Descrição é obrigatória.',
            'descricao.max' => '255 caracteres permitidos.',
            'grau.max' => '1 caracteres permitido.'
        ];
        $this->validate($request, $rules, $messages);
    }

    public function delete($id){
        $item = Recompensa::findOrFail($id);

        try {
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/recompensas');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/recompensas');
        }
    }

    public function alunoLogin(){
        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);

        $ultimaGraduacao = $aluno->ultimaGraduacao;

        $data = Recompensa::
        select('recompensas.*')
        ->where('faixa_id', $ultimaGraduacao->faixa_id)
        ->get();
        return view('recompensas/view_alunos', compact('data'));

    }

    public function historico(){
        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);

        return view('recompensas/historico', compact('aluno'));

    }
}
