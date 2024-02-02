<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Aluno;
use App\Models\AlunoExame;
use App\Models\Recompensa;
use App\Models\Mensalidade;
use App\Models\Aviso;
use App\Models\AvisoView;
use App\Models\ComentarioVideo;
use App\Models\Configuracao;
use App\Models\AvisoGraduacaoMaster;
use App\Models\PedidoItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        view()->composer('*',function($view){

            $user = session('user_logged');

            if($user){
                $aluno = Aluno::findOrFail($user['aluno']->id);
                $recompensa = $this->verificaRecompensa($aluno);
                $recompensasParaMaster = $this->recompensasParaMaster($aluno);
                $exame = $this->verificaExameFaixa($aluno);
                $mensalidadeAtraso = $this->mensalidadeAtraso($aluno);
                $avisos = $this->getAvisos($aluno);
                $aniversariantes = $this->aniversariantes();
                $comentarios = $this->getDuvidasComentario($aluno);
                $comentariosRespondidos = $this->getDuvidasComentarioRespondido($aluno);

                $alertas = [];

                // if($recompensa){
                //     $temp = [
                //         'data' => date('d/m/Y'),
                //         'mensagem' => $recompensa->descricao . " | faixa: " . $recompensa->faixa->nome . " " . 
                //         ($recompensa->grau > 0 ? $recompensa->grau . "º grau" : "")
                //     ];

                //     array_push($alertas, $temp);
                // }

                // if(sizeof($recompensasParaMaster) > 0){
                //     foreach($recompensasParaMaster as $r){
                //         $temp = [
                //             'data' => date('d/m/Y'),
                //             'mensagem' => $r['mensagem'],
                //             'remove' => true,
                //             'aviso_master' => $r['aviso_master']
                //         ];

                //         array_push($alertas, $temp);
                //     }
                // }

                if($exame){
                    $temp = [
                        'data' => \Carbon\Carbon::parse($exame->created_at)->format('d/m/Y H:i'),
                        'mensagem' => "Exame de faixa " . $exame->exame->faixa->nome,
                        'link' => '/exames/view/'.$exame->exame->id
                    ];

                    array_push($alertas, $temp);
                }

                if($mensalidadeAtraso == true){
                    $temp = [
                        'data' => date('d/m/Y'),
                        'mensagem' => "Mensalidade em atraso seu acesso pode ser bloqueado!"
                    ];

                    array_push($alertas, $temp);
                }

                if(sizeof($avisos) > 0){
                    foreach($avisos as $av){
                        $temp = [
                            'data' => \Carbon\Carbon::parse($av->created_at)->format('d/m/Y H:i'),
                            'mensagem' => $av->titulo,
                            'link' => '/aviso/view/'.$av->id
                        ];
                        array_push($alertas, $temp);
                        
                    }
                }

                if(sizeof($aniversariantes) > 0){
                    if(__insMaster($aluno->email)){
                        foreach($aniversariantes as $a){
                            $temp = [
                                'data' => date('d/m/Y'),
                                'mensagem' => 'Aniversário de ' . $a->full_name,
                            ];
                            array_push($alertas, $temp);

                        }
                    }
                }

                if($comentarios > 0){
                    $temp = [
                        'data' => date('d/m/Y H:i'),
                        'mensagem' => 'Existem dúvidas/comentários para serem respondidos',
                        'link' => '/posicao/respostas'
                    ];
                    array_push($alertas, $temp);
                }

                if(sizeof($comentariosRespondidos) > 0){
                    if(__insMaster($aluno->email)){
                        
                        foreach($comentariosRespondidos as $c){

                            $temp = [
                                'data' => \Carbon\Carbon::parse($c->updated_at)->format('d/m/Y H:i'),
                                'mensagem' => 'Seu comentário na posição '.$c->posicao->nome.' foi respondido',
                                'link' => '/posicao/view/'.$c->posicao->id
                            ];
                            array_push($alertas, $temp);
                        }
                    }
                }

                $faixaImage = strtolower($aluno->ultimaGraduacao->faixa->nome) . "_" .$aluno->ultimaGraduacao->grau;

                if(str_contains($faixaImage, "cinza")){
                    $faixaImage = "cinza";
                }

                if(str_contains($faixaImage, "amarela")){
                    $faixaImage = "amarela";
                }

                if(str_contains($faixaImage, "laranja")){
                    $faixaImage = "laranja";
                }

                if(str_contains($faixaImage, "verde")){
                    $faixaImage = "verde";
                }

                if($aluno->sexo == 'f'){
                    if($aluno->modIsDef() && !$aluno->modIsJiu()){
                        $faixaImage = "";
                    }
                }

                $itensCarrinho = $this->getItensCarrinho($aluno);
                $view->with('faixa', $aluno->ultimaGraduacao->faixa->nome);
                $view->with('grau', $aluno->ultimaGraduacao->grau);
                $view->with('faixaImage', $faixaImage);
                $view->with('itensCarrinho', $itensCarrinho);
                $view->with('alertas', $alertas);
            }
        });

}

private function verificaRecompensa($aluno){
    $presencas = sizeof($aluno->treinos);
    $ultimaGraduacao = $aluno->ultimaGraduacao;
    $ultimaRecompensa = null;
    $minimoPresencasGraduacao = 0;
    if($ultimaGraduacao){
        $ultimaRecompensa = Recompensa::where('faixa_id', $ultimaGraduacao->faixa_id)
        ->where('grau', $ultimaGraduacao->grau)->first();

        $minimoPresencas = $ultimaRecompensa != null ? $ultimaRecompensa->total_presencas : 0;
    }

    if($presencas < $minimoPresencas){
        $presencas = $minimoPresencas + $presencas;
    }

    $recompensa = Recompensa::where('total_presencas', '<=', $presencas)
    ->where('total_presencas', '>=', $ultimaRecompensa != null ? $ultimaRecompensa->total_presencas : 0)
    ->where('id', '!=', $ultimaRecompensa != null ? $ultimaRecompensa->id : 0)->first();
    return $recompensa;
}

private function recompensasParaMaster($aluno){
    if(__insMaster($aluno->email)){

        $alunos = Aluno::
        where('status', 1)
        ->get();

        $recompensas = [];
        foreach($alunos as $aluno){
            $presencas = sizeof($aluno->treinos);
            $ultimaGraduacao = $aluno->ultimaGraduacao;
            $ultimaRecompensa = null;
            $minimoPresencasGraduacao = 0;
            if($ultimaGraduacao){
                $ultimaRecompensa = Recompensa::where('faixa_id', $ultimaGraduacao->faixa_id)
                ->where('grau', $ultimaGraduacao->grau)->first();

                $minimoPresencas = $ultimaRecompensa != null ? $ultimaRecompensa->total_presencas : 0;
            }
            if($presencas < $minimoPresencas){
                $presencas = $minimoPresencas + $presencas;
            }

            $recompensa = Recompensa::where('total_presencas', '<=', $presencas)
            ->where('total_presencas', '>=', $ultimaRecompensa != null ? $ultimaRecompensa->total_presencas : 0)
            ->where('id', '!=', $ultimaRecompensa != null ? $ultimaRecompensa->id : 0)->first();

            if($recompensa != null){
                $not = AvisoGraduacaoMaster::
                where('aluno_id', $aluno->id)
                ->where('faixa_id', $recompensa->faixa_id)
                ->where('grau', $recompensa->grau)
                ->first();
                if($not == null){
                    array_push($recompensas, [
                        'mensagem' => $aluno->full_name . " | faixa: " . $recompensa->faixa->nome . " " . ($recompensa->grau > 0 ? $recompensa->grau . "º grau" : ""),
                        'aviso_master' => "$aluno->id;$recompensa->id"
                    ]);
                }
            }
        }
        return $recompensas;

    }
    return [];
}

private function verificaExameFaixa($aluno){
    $exame = AlunoExame::where('aluno_id', $aluno->id)
    ->where('status', 0)
    ->first();

    return $exame;
}

private function mensalidadeAtraso($aluno){

    if(__insMaster($aluno->email)){
        return false;
    }

    if($aluno->cidade->nome == 'Jaguariaíva'){
        return false;
    }

    $pag = Mensalidade::
    whereMonth('data_pagamento', '=', date('m'))
    ->whereYear('data_pagamento', '=', date('Y'))
    ->where('aluno_id', $aluno->id)
    ->first();
    $config = Configuracao::first();

    if($pag == null){
        $ult = Mensalidade::
        where('aluno_id', $aluno->id)
        ->orderBy('id', 'desc')
        ->first();

        if($config->dia_pagamento <= date('d'))
            return true;
    }
    return false;
}

private function getAvisos($aluno){

    $avisos = Aviso::orderBy('id', 'desc')->limit(3)->get();

    $retorno = [];
    foreach($avisos as $a){

        $v = AvisoView::where('aluno_id', $aluno->id)
        ->where('aviso_id', $a->id)
        ->first();

        if($v == null){
            array_push($retorno, $a);
        }

    }
    return $retorno;
}

private function getDuvidasComentario($aluno){
    if(!__insMaster($aluno->email)) return 0;

    $coments = ComentarioVideo::orderBy('id', 'desc')
    ->where('resposta', '=', '')
    ->count();

    return $coments;
}

private function getDuvidasComentarioRespondido($aluno){

    $coments = ComentarioVideo::orderBy('id', 'desc')
    ->where('resposta_view', 0)
    ->where('aluno_id', $aluno->id)
    ->where('resposta', '!=', '')
    ->get();

    return $coments;
}

private function getItensCarrinho($aluno){

    $count = PedidoItem::
    join('pedidos', 'pedidos.id', '=', 'pedido_items.pedido_id')
    ->where('pedidos.aluno_id', $aluno->id)
    ->where('pedidos.carrinho', 1)
    ->count();

    return $count;
}

private function aniversariantes(){

    return Aluno::
    whereMonth('data_nascimento', date('m'))
    ->whereDay('data_nascimento', date('d'))
    ->get();

}
}
