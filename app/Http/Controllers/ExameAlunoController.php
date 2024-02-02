<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlunoExame;
use App\Models\AlunoExamePosicao;
use App\Models\Aluno;
class ExameAlunoController extends Controller
{
    public function index(Request $request){
        $data = AlunoExame::orderBy('id', 'desc')
        ->when(!empty($request->search), function ($q) use ($request) {
            return $q->join('alunos', 'alunos.id', '=', 'aluno_exames.aluno_id')
            ->where('nome', 'LIKE', "%$request->search%");

        })
        ->when(!empty($request->status), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('status', $status);
            });
        })
        ->get();
        return view('exame-aluno/index', compact('data'));
    }

    public function delete($id){
        $item = AlunoExame::findOrFail($id);

        try {
            $item->posicoes()->delete();
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/exames-aluno');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/exames-aluno');
        }
    }

    public function start($id){
        $item = AlunoExame::findOrFail($id);
        if($item->status){
            session()->flash("flash_erro", "Exame já esta finalizado!");
            return redirect('/exames-aluno');
        }
        return view('exame-aluno/start', compact('item'));
    }

    public function alterarStatus($id){
        $item = AlunoExamePosicao::findOrFail($id);

        $item->status = !$item->status;
        $item->save();
        session()->flash("flash_sucesso", "Status alterado");
        return redirect()->back();
    }

    public function finalizar($id){
        $item = AlunoExame::findOrFail($id);

        return view('exame-aluno/finalizar', compact('item'));
    }

    public function finalizarPut(Request $request, $id){
        $item = AlunoExame::findOrFail($id);

        try{
            $item->resultado = $request->resultado;
            $item->observacao = $request->observacao ?? '';
            $item->status = true;

            $item->save();
            session()->flash("flash_sucesso", "Exame finalizado!");
            return redirect('/exames-aluno');

        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->back();
        }

    }
}
