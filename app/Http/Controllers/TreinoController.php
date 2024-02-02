<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Agenda;
use App\Models\Treino;
use App\Models\Configuracao;
use App\Models\AlunoTreino;

class TreinoController extends Controller
{
    public function confirmarPresenca($treino_id){
        $user = session('user_logged');

        try{
            $config = Configuracao::first();
            $treino = Treino::findOrFail($treino_id);
            $aluno = Aluno::findOrFail($user['aluno']->id);

            $minutos_para_presenca = $config->minutos_para_presenca;
            $dataTreino = date('Y-m-d') . ' ' .$treino->agenda->horario;
            $dataAtual = date('Y-m-d H:i');

            $dif = strtotime($dataTreino) - strtotime($dataAtual);
            $dif = $dif/60;

            
            if($dif <= $minutos_para_presenca){
                $dif = $dif * -1;

                if($dif <= $minutos_para_presenca){

                    $temp = AlunoTreino::where('aluno_id', $aluno->id)
                    ->where('treino_id', $treino->id)
                    ->first();
                    if($temp != null){
                        session()->flash("flash_erro", "Não é possível confirmar novamente");
                        return redirect('/dashboard');
                    }else{
                        AlunoTreino::create([
                            'aluno_id' => $aluno->id,
                            'treino_id' => $treino->id,
                            'status' => 0
                        ]);
                    }

                    session()->flash("flash_sucesso", "Presença confirmada!");
                }else{
                    session()->flash("flash_erro", "Tempo esgotado para confirmar presença!");
                    return redirect('/dashboard');
                }
            }else{
                session()->flash("flash_erro", "Você pode confirmar a presença somente $minutos_para_presenca minutos antes do treino");
                return redirect('/dashboard');
            }
            return redirect('/dashboard');
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/dashboard');
        }
    }

    public function confirmarTreino(Request $request){
        $agenda = Agenda::findOrFail($request->agenda_id);
        try{
            $data = [
                'agenda_id' => $agenda->id,
                'descricao' => $request->descricao ?? '',
                'data' => $agenda->date(),
                'status' => true
            ];

            Treino::create($data);
            session()->flash("flash_sucesso", "Treino confirmado!");
            return redirect('/cronograma');
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/cronograma');
        }
    }

    public function desmarcarTreino(Request $request){
        $treino = Treino::findOrFail($request->treino_id);
        try{
            $treino->status = false;
            $treino->descricao = $request->descricao ?? '';
            $treino->save();
            session()->flash("flash_sucesso", "Treino desmarcado!");
            return redirect('/cronograma');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/cronograma');
        }
    }

    public function reconfirmar(Request $request){
        $treino = Treino::findOrFail($request->treino_id);
        try{
            $treino->status = true;
            $treino->descricao = $request->descricao ?? '';
            $treino->save();

            session()->flash("flash_sucesso", "Treino reconfirmado!");
            return redirect('/cronograma');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/cronograma');
        }
    }

    public function index(){
        // $treinos = Treino::all();

        $agenda = Agenda::all();
        foreach($agenda as $a){
            $t = Treino::where('data', $a->date())
            ->where('agenda_id', $a->id)
            ->first();
            $a->confirmado = $t != null;
            $a->descricao = $t != null ? $t->descricao : '';
            $a->treino_id = $t != null ? $t->id : 0;
            $a->status = $t != null ? $t->status : false;

        }
        return view('cronograma/index', compact('agenda'));
    }
}
