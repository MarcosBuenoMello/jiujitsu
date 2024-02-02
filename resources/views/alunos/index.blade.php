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

				<a href="/aluno/new" class="btn btn-success ml-1 mb-3">
					<i class="la la-plus"></i> Cadastrar aluno(a)
				</a>

				<a href="/escolas" class="btn btn-warning ml-1 mb-3">
					<i class="la la-school"></i> Escolas
				</a>
				<h1 class="h3 mb-2 text-gray-800">Alunos</h1>

				@foreach($desativados as $a)
				<p class="text-danger">Aluno desativado <strong>{{$a->full_name}}</strong></p>
				@endforeach

				<p>Somatório de mensalidades: 
					<strong class="text-success">R$ {{ number_format($somaMensalidade->total, 2, ',', '.') }}</strong>
				</p>
				<p>Total de alunos ativos: <strong class="text-primary">{{$count}}</strong></p>
				<p>Total de alunos inátivos: <strong class="text-danger">{{$countDesativados}}</strong></p>
				<p>Total de alunos com push habilitado: <strong class="text-primary">{{$countToken}}</strong></p>

				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">
					<div class="col-md-3">
						{!!Form::text('search', 'Pesquisar por nome')
						!!}
					</div>
					<div class="col-md-2">
						{!!Form::select('faixa_id', 'Faixa', [null => 'Selecione...'] + $faixas->pluck('nome',
						'id')->all())
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('cidade_id', 'Cidade', [null => 'Selecione...'] + $cidades->pluck('nome',
						'id')->all())
						!!}
					</div>
					<div class="col-md-2">
						{!!Form::select('status', 'Status', ['' => 'Selecione...', '1' => 'Ativo', '-1' => 'Inátivo'])
						!!}
					</div>

					<div class="col-md-3 text-left mt-1">
						<br>
						<button class="btn btn-sm  btn-primary" style="font-size: 9px;" type="submit"><svg
							xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor"
							class="bi bi-funnel-fill" viewBox="0 0 16 16">
							<path
							d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
						</svg> Filtrar</button>
						<a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger"
						href="{{ route('aluno.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
						height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
						<path
						d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
					</div>
				</div>

				<div class="col-12 mt-2"></div>
				{!!Form::close()!!}

				<div class="row">
					@forelse($alunos as $aluno)
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card {{$aluno->faixa_border()}} shadow h-100">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">{{$aluno->nome}} {{$aluno->sobre_nome}}</h6>
								<div class="dropdown no-arrow">
									<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"aria-labelledby="dropdownMenuLink">

										<a class="dropdown-item text-warning" href="{{ route('aluno.edit', $aluno->id) }}"><i class="la la-edit mr-2"></i>Editar</a>

										<form action="{{ route('aluno.delete', $aluno->id) }}" method="post"
											id="form-{{$aluno->id}}">
											@csrf
											<button type="button" class="dropdown-item btn-delete text-danger">
												<i class="la la-trash mr-2"></i>Remover
											</button>
										</form>

										<a class="dropdown-item text-info" href="{{ route('aluno.note', $aluno->id) }}"><i class="la la-archive mr-2"></i>Anotações</a>
										<a class="dropdown-item text-primary" href="{{ route('aluno.detail', $aluno->id) }}"><i class="la la-info mr-2"></i>Detalhes</a>

										<a class="dropdown-item text-success" href="{{ route('aluno.historic', $aluno->id) }}"><i class="la la-list mr-2"></i>Histórico</a>

										@if($aluno->escola_id)
										<a class="dropdown-item text-info" href="{{ route('aluno.boletin', $aluno->id) }}"><i class="la la-school mr-2"></i>Boletim</a>
										@endif

										<a class="dropdown-item text-black" href="{{ route('aluno.push', $aluno->id) }}"><i class="la la-bell mr-2"></i>Push</a>

									</div>
								</div>
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
											Cidade: <strong>
												{{ $aluno->cidade->nome}} 
												@if($aluno->cidade_id2 != null)
												- {{ $aluno->cidade2->nome }}
												@endif
											</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800 mb-2">
											Valor mensalidade: <strong>R$ {{ number_format($aluno->valor_mensalidade, 2, ',', '.') }}</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800 mb-2">
											Data nascimento: <strong>{{ $aluno->data_nascimento != null ? \Carbon\Carbon::parse($aluno->data_nascimento)->format('d/m/Y') : '--'}}</strong>
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
					<p class="ml-3">Nenhum registro encontrado!</p>
					@endforelse

				</div>
			</div>
			<div class="col-12">
				<nav class="d-flex justify-content-end" aria-label="...">
					{{ $alunos->appends(request()->all())->links() }}
				</nav>
			</div>
		</div>
	</div>
</div>

@endsection

