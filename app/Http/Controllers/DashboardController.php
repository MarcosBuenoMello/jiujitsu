<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Agenda;
use App\Models\Posicao;
use App\Models\Cidade;
use App\Models\Treino;
use App\Models\AlunoAcesso;
use App\Models\AlunoTreino;
use App\Models\Recompensa;
use App\Models\AvisoGraduacaoMaster;
use Illuminate\Support\Str;
use App\Models\Configuracao;

class DashboardController extends Controller
{
    public function index(){

        $alunosPendentes = Aluno::where('status', 0)
        ->get();

        $diasSemana = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];
        $diaSemanaNumero = date('w', strtotime(date('Y-m-d')));
        $user = session('user_logged');
        $sexoAluno = $user['aluno']->sexo;
        $cidade = $user['aluno']->cidade;
        $cidade2 = $user['aluno']->cidade2;
        $id = $user['aluno']->id;

        if(__insMaster($user['aluno']->email)){
            $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
            ->get();
        }else{

            $aluno = $user['aluno'];
            if($aluno->modIsJiu() && !$aluno->modIsDef()){
                $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
                ->where('cidade_id', $cidade->id)
                ->select('agendas.*')
                ->where('modalidade_id', '<', 3)
                ->get();

                if($cidade2 != null){
                    $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
                    ->select('agendas.*')
                    ->where('modalidade_id', '<', 3)
                    ->get();
                }
            }else if(!$aluno->modIsJiu() && $aluno->modIsDef()){
                $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
                ->where('cidade_id', $cidade->id)
                ->select('agendas.*')
                ->where('modalidade_id', 3)
                ->get();

                if($cidade2 != null){
                    $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
                    ->select('agendas.*')
                    ->where('modalidade_id', 3)
                    ->get();
                }

            }else{
                $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
                ->where('cidade_id', $cidade->id)
                ->get();

                if($cidade2 != null){
                    $agenda = Agenda::where('dia_semana', $diasSemana[$diaSemanaNumero])
                    ->get();
                }
            }

        }

        $posicoes = Posicao::orderBy('id', 'desc')->limit(6)->get();
        $master = __insMaster($user['aluno']->email);
        return view('dashboard/index', compact('alunosPendentes', 'agenda', 'sexoAluno',
            'posicoes', 'master', 'id'));
    }

    public function update(Request $request){
        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);

        $aluno->nome = $request->nome;
        $aluno->sobre_nome = $request->sobre_nome;
        $aluno->peso_atual = $request->peso_atual;
        $aluno->sexo = $request->sexo;
        $aluno->cidade_id = $request->cidade_id;
        if($request->data_nascimento){
            $d = str_replace("/", "-", $request->data_nascimento);
            $aluno->data_nascimento = \Carbon\Carbon::parse($d)->format('Y-m-d');
        }

        $aluno->save();

        session()->flash("flash_sucesso", "Dados salvos");
        return redirect()->back();
    }

    public function perfil(){
        $user = session('user_logged');
        $cidades = Cidade::all();

        $aluno = Aluno::findOrFail($user['aluno']->id);

        $ultimaGraduacao = $aluno->ultimaGraduacao;
        $treinosPendentes = 0;
        $proxGraduacao = null;
        $presencas = sizeof($aluno->treinos);

        if($ultimaGraduacao){

            $proxGraduacao = $this->proximaGraduacao($ultimaGraduacao);

        // total_presencas

            $ultimaRecompensa = null;
            $minimoPresencasGraduacao = 0;
            $ultimaRecompensa = Recompensa::where('faixa_id', $ultimaGraduacao->faixa_id)
            ->where('grau', $ultimaGraduacao->grau)->first();

            $presencas = $aluno->treinosPosGraucao($ultimaGraduacao->data);

            $minimoPresencas = $ultimaRecompensa != null ? $ultimaRecompensa->total_presencas : 0;
        }
        if($presencas < $minimoPresencas){
            $presencas = $minimoPresencas + $presencas;
        }

        if($proxGraduacao){
            $treinosPendentes = $proxGraduacao->total_presencas - $presencas;
        }
        return view('dashboard/perfil', compact('cidades', 'aluno', 'proxGraduacao', 
            'treinosPendentes'));
    }

    private function proximaGraduacao($ultimaGraduacao){
        $grau = $ultimaGraduacao->grau;
        $faixa_id = $ultimaGraduacao->faixa_id;

        if($grau == 4){ 
            $grau = 0;
            $faixa_id++;
        }else{
            $grau++;
        }

        return Recompensa::where('faixa_id', $faixa_id)
        ->where('grau', $grau)->first();
    }

    public function salvarFoto(Request $request){
        if($request->hasFile('file')){
            $file = $request->file('file');

            $user = session('user_logged');
            $aluno = Aluno::findOrFail($user['aluno']->id);

            if($aluno->imagem != ""){
                if(file_exists(public_path('alunos/').$aluno->imagem)){
                    unlink(public_path('alunos/').$aluno->imagem);
                }
            }

            $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();

            $aluno->imagem = $fileName;
            $aluno->save();

            $file->move(public_path('alunos'), $fileName);

            session()->flash("flash_sucesso", "Imagem alterada");
            return redirect()->back();
        }else{
            session()->flash("flash_erro", "Algo esta errado!");
            return redirect()->back();
        }
    }

    public function cronogramaPresenca(){
        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);
        $grade = $this->createGrade($aluno);

        $treinosDoAno = $this->countTreinosDoAno();
        $treinosDoAnoAluno = $this->countTreinosDoAnoAluno($aluno->id);

        $totalDeTreinos = $treinosDoAno != null ? $treinosDoAno->cont : 0;
        $totalDeTreinosDoAluno = $treinosDoAnoAluno != null ? $treinosDoAnoAluno->cont : 0;
        $percentual = 0;
        if($totalDeTreinosDoAluno > 0){
            $percentual = number_format(100-((($totalDeTreinos-$totalDeTreinosDoAluno)/$totalDeTreinos)
            *100),2);
        }
        return view('dashboard/cronograma', compact('aluno', 'grade', 'totalDeTreinos', 
            'totalDeTreinosDoAluno', 'percentual'));
    }

    private function createGrade($aluno){
        $dias = 31;
        $linhas = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        $grade = [];
        foreach($linhas as $keyMes => $l){
            $grade[$l] = [];
            for($aux=1; $aux<=$dias; $aux++){
                $marca = $this->searchTreino($keyMes+1, $aux, $aluno->id);

                $temp['dia'] = $aux;
                $temp['status'] = 0;
                if($marca){
                    $temp['status'] = 1;
                }
                array_push($grade[$l], $temp);
            }
        }
        return $grade;
    }

    private function searchTreino($mes, $dia, $aluno_id){
        $data = date('Y')."-". ($mes < 10 ? "0".$mes : $mes) ."-". ($dia < 10 ? "0".$dia : $dia);
        $treinos = Treino::whereDate('data', $data)->where('status', 1)->get();
        
        if(sizeof($treinos) > 0){
            if(sizeof($treinos) == 1){
                $treino = $treinos[0];
                $alunoTreino = AlunoTreino::
                where('aluno_id', $aluno_id)
                ->where('status', 1)
                ->where('treino_id', $treino->id)
                ->first();

                return $alunoTreino;
            }else{
                foreach($treinos as $t){
                    $alunoTreino = AlunoTreino::
                    where('aluno_id', $aluno_id)
                    ->where('status', 1)
                    ->where('treino_id', $t->id)
                    ->first();
                    if($alunoTreino != null) return $alunoTreino;
                }
            }
        }
        return false;
    }

    private function countTreinosDoAno(){
        return Treino::
        selectRaw('count(*) as cont')
        ->whereYear('created_at', date('Y'))
        ->where('status', 1)->first();
    }

    private function countTreinosDoAnoAluno($aluno_id){
        return AlunoTreino::
        selectRaw('count(*) as cont')
        ->whereYear('created_at', date('Y'))
        ->where('aluno_id', $aluno_id)
        ->where('status', 1)->first();
    }

    public function graficoDias(){
        $mes = date('m');
        $dia = date('d');
        $ano = date('Y');

        $labels = [];
        $values = [];
        $maximo_acessos = 0; 
        for($i=1; $i<=$dia; $i++){
            $data = ($i<10 ? "0".$i : $i) . "/$mes";
            array_push($labels, $data);

            $d = "$ano-$mes-" . ($i<10 ? "0".$i : $i);
            $acessos = AlunoAcesso::
            whereBetween('created_at', [
                $d . " 00:00:00",
                $d . " 23:59:59",
            ])
            ->count();

            if($acessos > $maximo_acessos) $maximo_acessos = $acessos;
            array_push($values, $acessos);

        }

        $arr = [
            'labels' => $labels,
            'data' => $values,
            'maximo_acessos' => $maximo_acessos
        ];
        return response()->json($arr, 200);
    }

    public function graficoFaixas($id){

        $aluno = Aluno::findOrFail($id);
        $presencas = sizeof($aluno->treinos);

        $ultimaGraduacao = $aluno->ultimaGraduacao;
        $ultimaRecompensa = null;
        $minimoPresencasGraduacao = 0;
        if($ultimaGraduacao){
            $ultimaRecompensa = Recompensa::where('faixa_id', $ultimaGraduacao->faixa_id)
            ->where('grau', $ultimaGraduacao->grau)->first();

            $minimoPresencas = $ultimaRecompensa != null ? $ultimaRecompensa->total_presencas : 0;

            $presencas = $aluno->treinosPosGraucao($ultimaGraduacao->data);
        }

        // $presencas = 10;
        if($presencas < $minimoPresencas){
            $presencas = $minimoPresencas + $presencas;
        }
        // $presencas = 40;
        // $totalBranca = Recompensa::totalBranca();
        $totalBranca = Recompensa::primeiraAzul();
        // $totalAzul = Recompensa::totalAzul();
        $totalAzul = Recompensa::primeiraRoxa();
        // $totalRoxa = Recompensa::totalRoxa();
        $totalRoxa = Recompensa::primeiraMarrom();

        // $totalMarrom = Recompensa::totalMarrom();
        $totalMarrom = Recompensa::primairaPreta();
        $totalPreta = Recompensa::totalPreta();

        $perc = 0;

        $colors = [
            "rgba(250, 250, 250, 0.3)",
            "rgba(0, 74, 173, 0.3)", 
            "rgba(96, 35, 116, 0.3)", 
            "rgba(77, 49, 49, 0.3)", 
            "rgba(0, 0, 0, 0.3)"
        ];

        $labels = [
            "Faixa branca", "Faixa azul", "Faixa roxa", "Faixa marrom", "Faixa preta"
        ];

        $data = [
            $totalBranca, $totalAzul, $totalRoxa, $totalMarrom, $totalPreta
        ];

        // $presencas += 10;

        $andamento = 0;
        $restante = 0;
        if($ultimaGraduacao->faixa->nome == 'Branca'){
            $primeiraAzul = Recompensa::primeiraAzul();
            // $perc = 100-((($primeiraAzul-$presencas)/$primeiraAzul)*100);

            $andamento = $presencas;
            $restante = $primeiraAzul - $presencas;
            $data = $this->addByIndice($data, 0, $presencas, $primeiraAzul - $presencas);
            $colors = $this->addColorByIndice($colors, 0, "rgba(213, 215, 222)");
            $labels = $this->addLabelrByIndice($labels, 1, "Graduar faixa azul");
        }

        if($ultimaGraduacao->faixa->nome == 'Azul'){

            $primeiraRoxa = Recompensa::primeiraRoxa();

            // $perc = 100-((($totalAzul-$presencas)/$totalAzul)*100);
            $andamento = $presencas-$totalBranca;
            $restante = $primeiraRoxa - $presencas;
            $data = $this->addByIndice($data, 1, $presencas-$totalBranca, $primeiraRoxa - $presencas);

            $colors = $this->addColorByIndice($colors, 1, "rgba(0, 74, 173)");
            $labels = $this->addLabelrByIndice($labels, 2, "Graduar faixa roxa");
        }

        if($ultimaGraduacao->faixa->nome == 'Roxa'){
            $primeiraMarrom = Recompensa::primeiraMarrom();

            // $perc = 100-((($totalRoxa-$presencas)/$totalRoxa)*100);
            $andamento = $presencas-$totalAzul;
            $restante = $primeiraMarrom - $presencas;
            $data = $this->addByIndice($data, 2, $presencas-$totalAzul, $primeiraMarrom - $presencas);
            $colors = $this->addColorByIndice($colors, 2, "rgba(96, 35, 116)");
            $labels = $this->addLabelrByIndice($labels, 3, "Graduar faixa marrom");
        }

        if($ultimaGraduacao->faixa->nome == 'Marrom'){
            $primeiraPreta = Recompensa::primeiraPreta();

            $andamento = $presencas-$totalRoxa;
            $restante = $primeiraPreta - $presencas;
            $perc = 100-((($totalMarrom-$presencas)/$totalMarrom)*100);
            $data = $this->addByIndice($data, 3, $presencas-$totalRoxa, $primeiraPreta - $presencas);
            $colors = $this->addColorByIndice($colors, 3, "rgba(77, 49, 49)");
            $labels = $this->addLabelrByIndice($labels, 4, "Graduar faixa preta");
        }

        if($ultimaGraduacao->faixa->nome == 'Preta'){
            $primeiraPreta = Recompensa::primeiraPreta();

            $andamento = $presencas-$totalMarrom;
            $restante = 0;
            $perc = 100-((($totalPreta-$presencas)/$totalPreta)*100);
            $data = $this->addByIndice($data, 4, $presencas-$totalMarrom, $totalPreta - $presencas);
            $colors = $this->addColorByIndice($colors, 4, "rgba(77, 49, 49)");
            $labels = $this->addLabelrByIndice($labels, 5, "");
        }

        $arr = [
            // 'total_branca' => $totalBranca,
            // 'total_azul' => $totalAzul,
            // // 'div2' => $div2,
            // 'total_roxa' => $totalRoxa,
            // 'total_marrom' => $totalMarrom,
            // 'total_preta' => $totalPreta,
            'presencas' => $presencas,
            'colors' => $colors,
            'labels' => $labels,
            'data' => $data,
            'perc' => (float)number_format($perc, 2),
            'andamento' => $andamento,
            'restante' => $restante

        ];  
        return response()->json($arr, 200);

    }

    private function addByIndice($arr, $indice, $valor, $dif){
        // if($valor < 10) $valor = 10;
        $temp = [];
        foreach($arr as $key => $a){
            if($indice == $key){
                array_push($temp, $valor);
                array_push($temp, $dif);
            }else{
                array_push($temp, $a);
            }

        }
        return $temp;
    }

    private function addColorByIndice($arr, $indice, $color){

        $colors = [
            "rgba(250, 250, 250)",
            "rgba(0, 74, 173)", 
            "rgba(96, 35, 116)", 
            "rgba(77, 49, 49)", 
            "rgba(0, 0, 0)"
        ];

        $temp = [];
        foreach($arr as $key => $a){
            if($indice > $key){
                array_push($temp, $colors[$key]);
            }else{
                if($indice == $key){
                    array_push($temp, $colors[$key]);
                }
                // array_push($temp, "#000");
                array_push($temp, $a);
            }

        }
        return $temp;
    }

    private function addLabelrByIndice($arr, $indice, $label){
        $temp = [];
        foreach($arr as $key => $a){
            if($indice == $key){
                array_push($temp, $label);
            }
            array_push($temp, $a);

        }
        return $temp;
    }

    public function notifyAlert($n){
        $aluno_id = explode(";", $n)[0];
        $recompensa_id = explode(";", $n)[1];

        $recompensa = Recompensa::findOrFail($recompensa_id);

        AvisoGraduacaoMaster::create([
            'aluno_id' => $aluno_id,
            'faixa_id' => $recompensa->faixa_id,
            'grau' => $recompensa->grau
        ]);
        return redirect()->back();
    }

    public function dataAniversario($id){
        $aluno = Aluno::findOrFail($id);
        if($aluno->data_nascimento == null){
            return response()->json("", 404);
        }
        return response()->json("", 200);
    }

    public function updateNiver(Request $request){
        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);

        if($request->data){
            $data = str_replace("/", "-", $request->data);
            $aluno->data_nascimento = \Carbon\Carbon::parse($data)->format('Y-m-d');
            $aluno->save();
            session()->flash("flash_sucesso", "Data de nascimento definida!");

        }

        return redirect()->back();
    }
}
