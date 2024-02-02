@extends('default', ['title' => 'Detalhes'])
@section('content')
<style type="text/css">
	.square{
		border: 1px solid #000;
		padding: 5px;
		border-radius: 10px;
	}

	.check{
		background: #17A673;
		border: 1px solid #17A673;

	}
</style>
<div class="row">
	<div class="col-xl-12 order-xl-1">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h5 class="mb-0">Detalhes do aluno <strong>{{$item->nome}} {{$item->sobre_nome}}</strong></h5>
					</div>
					<div class="col-md-4 text-right">
						<a href="/aluno" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">

				<div class="card">
					<div class="card-header">
						<h4>Grade de frequência</h4>
					</div>
					<div class="card-body">
						<table class="table table-responsive">
							
							<tbody>
								@foreach($grade as $key => $g)
								<tr>
									<td></td>
									@foreach($g as $k => $dia)
									@if($key == 'Janeiro')
									<td>
										<label style="width: 14px; font-size: 11px;">{{$dia['dia']}}</label>
									</td>
									@endif
									@endforeach
								</tr>
								<tr>
									<td class="bg-primary text-white" style="font-size: 12px; ">{{$key}}</td>
									@foreach($g as $dia)
									<td style="padding: 12px;">
										<div class="square @if($dia['status']) check @endif"></div>
									</td>
									@endforeach
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-fotter">
						<div class="row">
							<div class="col-md-4 col-12">
								<h6 class="ml-2">Quantidade total de aulas: <strong>{{$totalDeTreinos}}</strong></h6>
							</div>
							<div class="col-md-4 col-12">
								<h6 class="ml-2">Quantidade de treinos do aluno: <strong>{{$totalDeTreinosDoAluno}}</strong></h6>
							</div>
							<div class="col-md-4 col-12">
								<h6 class="ml-2">% de presença: <strong>{{$percentual}}</strong></h6>
							</div>
						</div>
					</div>
				</div>

				<hr>

				<div class="card">
					<div class="card-header">
						<h4>Últimos acessos ao sistema</h4>
					</div>
					<div class="card-body">
						<table class="table">
							
							<thead>
								<tr>
									<th>Data</th>
									<th>IP</th>
								</tr>
							</thead>

							<tbody>
								@foreach($item->ultimosAcessos as $a)
								<tr>
									<td>{{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i') }}</td>
									<td>{{ $a->ip }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-fotter">
						<a class="btn btn-primary mb-2 ml-2" href="/aluno/acessosLogin/{{$item->id}}">
							Ver todos os acessos de login
						</a>
					</div>
				</div>

				<hr>

				<div class="card">
					<div class="card-header">
						<h4>Últimos acessos de posição</h4>
					</div>
					<div class="card-body">
						<table class="table">
							
							<thead>
								<tr>
									<th>Posição</th>
									<th>Categoria</th>
									<th>Data</th>
								</tr>
							</thead>

							<tbody>
								@foreach($item->ultimosAcessosPosicoes as $p)
								<tr>
									<td>{{$p->posicao->nome}}</td>
									<td>{{$p->posicao->categoria->nome}}</td>
									<td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i:s') }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-fotter">
						<a class="btn btn-primary mb-2 ml-2" href="/aluno/acessosPosicao/{{$item->id}}">
							Ver todos os acessos de posição
						</a>
					</div>
				</div>

				<hr>

				<div class="card">
					<div class="card-header">
						<h4>Últimos pagamentos de mensalidade</h4>
					</div>
					<div class="card-body">
						<table class="table">
							
							<thead>
								<tr>
									<th>Data de pagamento</th>
									<th>Valor</th>
									<th>Forma de pagamento</th>
								</tr>
							</thead>

							<tbody>
								@foreach($item->ultimosPagamentos as $p)
								<tr>
									<td>{{ \Carbon\Carbon::parse($p->data_pagamento)->format('d/m/Y')}}</td>
									<td>R$ {{number_format($p->valor, 2, ',', '.') }}</td>
									<td>{{ mb_strtoupper($p->forma_pagamento) }}</td>
								</tr>
								@endforeach
								
							</tbody>
						</table>
					</div>
					<div class="card-fotter">
						<a class="btn btn-primary mb-2 ml-2" href="/aluno/mensalidades/{{$item->id}}">
							Ver todos os pagamentos
						</a>
					</div>
				</div>

				<hr>

				<div class="card">
					<div class="card-header">
						<h4>Últimas contribuições</h4>
					</div>
					<div class="card-body">
						<table class="table">
							
							<thead>
								<tr>
									<th>Data de pagamento</th>
									<th>Valor</th>
								</tr>
							</thead>

							<tbody>
								@foreach($item->ultimasContribuicoes as $p)
								<tr>
									<td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y')}}</td>
									<td>R$ {{number_format($p->valor, 2, ',', '.') }}</td>
								</tr>
								@endforeach
								
							</tbody>
						</table>
					</div>
					<div class="card-fotter">
						<a class="btn btn-success mb-2 ml-2" href="/aluno/contribuicoes/{{$item->id}}">
							Ver todas contribuiçoes
						</a>
					</div>
				</div>

				<div class="card shadow mt-2">
					<input type="hidden" id="aluno_id" value="{{$item->id}}">

					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Progresso de graduação</h6>

					</div>
					<!-- Card Body -->
					<div class="card-body">
						@if($proxGraduacao)
						<h4>Próxima graduação: 
							<strong class="{{$proxGraduacao->faixa_border()}}">
							{{$proxGraduacao->faixa->nome}}	
							@if($proxGraduacao->grau > 0)
							{{$proxGraduacao->grau}}º grau
							@endif	
							</strong>
						</h4>

						<h5>Treinos para a próxima graduação: 
							<strong>{{$treinosPendentes}}</strong>
						</h5>
						@endif
						<div class="chart-pie pt-4 pb-2"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand">
							<div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
							<canvas id="myPieChart" style="display: block; width: 294px; height: 245px;" class="chartjs-render-monitor" width="294" height="245"></canvas>
						</div>
						<div class="mt-4 text-center small">
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #D9D9D9;"></i> Faixa branca
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #004AAD;"></i> Faixa azul
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #602374;"></i> Faixa roxa
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #4D3131;"></i> Faixa marrom
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #000;"></i> Faixa preta
							</span>

						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
@endsection

@section('js')
<script src="/vendor/chart.js/Chart.min.js"></script>
<script src="/js/chart-pie.js"></script>
@endsection
