@extends('default', ['title' => 'Alunos'])
@section('content')

<style type="text/css">
	.img-profile2{
		height: 120px;
		width: 120px !important;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<div class="row">
					<div class="col-12">
						<h1 class="h3 mb-2 text-gray-800 float-right">Presença de Aluno(s)</h1>
					</div>
				</div>

				
				<div class="row">
					@forelse($treino->alunos as $alunoTreino)

					@php
					$aluno = $alunoTreino->aluno;
					@endphp


					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card {{$aluno->faixa_border()}} shadow h-100">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">{{$aluno->full_name}}</h6>
								
							</div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Faixa: @if($aluno->ultimaGraduacao)<strong>{{$aluno->ultimaGraduacao->faixa->nome}} {{$aluno->ultimaGraduacao->grau > 0 ? $aluno->ultimaGraduacao->grau . "º" : ''}}</strong> @else -- @endif
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Peso: <strong>{{$aluno->peso_atual}} KG</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data de cadastro: <strong>{{\Carbon\Carbon::parse($aluno->created_at)->format('d/m/Y H:i')}}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800 mb-2">
											Cidade: <strong>{{ $aluno->cidade->nome}}</strong>
										</div>

										@if($aluno->status == false)
										<h5 class="text-danger">Aluno inátivo</h5>
										@endif
									</div>
									<div class="col-auto">
										@if($aluno->imagem == "")
										<img class="img-profile2 rounded-circle"
										src="/img/undraw_profile.svg">
										@else
										<img class="img-profile2 rounded-circle"
										src="/alunos/{{$aluno->imagem}}">
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>

					@empty
					<p class="ml-3">Nenhum aluno(a) foi ao treino!</p>
					@endforelse
					
				</div>
			</div>
			
		</div>
	</div>
</div>

@endsection

