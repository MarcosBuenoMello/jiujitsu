@extends('default', ['title' => 'Histórico de graduação'])
@section('content')


<style type="text/css">
	.img-profile{
		height: auto;
		width: 170px;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card w-100">
			<div class="card-body">

				<h1 class="h3 mb-2 text-gray-800">Histórico de graduação - <strong>{{$aluno->full_name}}</strong></h1>

				<div class="row mt-2">
					@forelse($aluno->graduacoes as $g)
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card h-100">

							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Faixa: <strong>{{ $g->faixa->nome }}</strong> 
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Grau: <strong>{{ $g->grau > 0 ? $g->grau : '-' }}</strong> 
										</div>
										
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data: <strong>{{\Carbon\Carbon::parse($g->data)->format('d/m/Y')}}</strong>
										</div>

										
									</div>
									<div class="col-auto">
										
										<img class="img-profile" src="/faixas/{{strtolower($g->faixa->nome)}}_{{$g->grau}}.png">
									</div>


								</div>
							</div>
						</div>
					</div>
					@empty
					<p class="ml-3">Nenhum registro encontrado!</p>
					@endforelse

				</div>
			</div>
			
		</div>
	</div>
</div>

@endsection