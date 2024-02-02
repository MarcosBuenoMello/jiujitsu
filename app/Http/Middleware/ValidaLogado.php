<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use App\Models\Mensalidade;
use App\Models\Configuracao;
class ValidaLogado
{

	public function handle($request, Closure $next){

		$value = session('user_logged');
		if(!$value){
			return redirect('/login');
		}

		// $aluno = $value['aluno'];
		// $pag = Mensalidade::
		// whereMonth('data_pagamento', '=', date('m'))
		// ->where('aluno_id', $aluno->id)
		// ->first();

		// if($pag == null && !$value['master']){

		// 	$config = Configuracao::first();

		// 	$ult = Mensalidade::
		// 	where('aluno_id', $aluno->id)
		// 	->orderBy('id', 'desc')
		// 	->first();

		// 	if($ult != null){

		// 		$dif = date('d')-$config->dia_pagamento;

		// 		if($dif > $config->dias_para_bloqueio){
		// 			session()->flash("flash_erro", "Acesso bloqueado!");
  //                   return redirect('/pagamento');
		// 		}
		// 	}else{

		// 		$dataAtual = date('Y-m-d H:i');
		// 		$dataCadastro = \Carbon\Carbon::parse($aluno->created_at)->format('Y-m-d H:i');

		// 		$dif = strtotime($dataAtual) - strtotime($dataCadastro);
		// 		$dif = (int) $dif/24/60/60;

		// 		if((int)$dif > 30){
		// 			session()->flash("flash_erro", "Acesso bloqueado!");
  //                   return redirect('/pagamento');
		// 		}

		// 	}
		// }

		return $next($request);
		
	}

}