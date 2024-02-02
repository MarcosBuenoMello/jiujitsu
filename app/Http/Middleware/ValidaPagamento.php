<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use App\Models\Mensalidade;
use App\Models\Configuracao;
use App\Models\Aluno;

class ValidaPagamento
{

	public function handle($request, Closure $next){

		$value = session('user_logged');
		$aluno = $value['aluno'];
		// return $next($request);
		
		if(__insMaster($aluno->email)){
			return $next($request);
		}

		$ult = Mensalidade::
		where('aluno_id', $aluno->id)
		->orderBy('id', 'desc')
		->first();

		$aluno = Aluno::findOrFail($aluno->id);
		
		// if($aluno->cidade->nome == 'Jaguariaíva'){
		// 	return $next($request);
		// }

		if($aluno->valor_mensalidade == 0){
			return $next($request);
		}

		if($ult != null){

			$config = Configuracao::first();

			$pag = Mensalidade::
			whereMonth('data_pagamento', '=', date('m'))
			->whereYear('data_pagamento', '=', date('Y'))
			->where('aluno_id', $aluno->id)
			->first();

			//verifica mes anterior

			if($pag != null){
				return $next($request);
			}
			
			$diasParaBloqueio = $config->dias_para_bloqueio;
			$diaPagamento = $config->dia_pagamento;

			$hoje = date('d');

			if($hoje > $diaPagamento + $diasParaBloqueio){
				session()->flash("flash_erro", "Acesso bloqueado, realize o pagamento!");
				return redirect('/pagamento');
			}

			$mesAntes = date('m')-1;
			if($mesAntes == 0) $mesAntes = 12;
			$mesAntes = $mesAntes < 10 ? "0".$mesAntes : $mesAntes;
			$pagMesAnterior = Mensalidade::
			whereMonth('data_pagamento', '=', $mesAntes)
			->whereYear('data_pagamento', '=', $mesAntes == 12 ? date('Y')-1: date('Y'))
			->where('aluno_id', $aluno->id)
			->first();

			// echo $mesAntes == "01" ? date('Y')-1: date('Y');
			// die;

			if($pagMesAnterior == null){
				session()->flash("flash_erro", "Acesso bloqueado, realize o pagamento do mês anterior!");
				return redirect('/pagamento');
			}

			
		}else{

			// $dataAtual = date('Y-m-d H:i');
			// $dataCadastro = \Carbon\Carbon::parse($aluno->created_at)->format('Y-m-d H:i');

			// $dif = strtotime($dataAtual) - strtotime($dataCadastro);
			// $dif = (int) $dif/24/60/60;
			// if((int)$dif > 30){
			// 	session()->flash("flash_erro", "Acesso bloqueado, realize o pagamento!");
			// 	return redirect('/pagamento');
			// }

			session()->flash("flash_erro", "Acesso bloqueado, realize o pagamento!");
			return redirect('/pagamento');

		}
		
		return $next($request);
		
	}

}