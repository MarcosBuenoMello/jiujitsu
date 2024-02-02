@extends('default', ['title' => 'Posiçoes'])
@section('content')

<style type="text/css">
	.img-profile{
		height: 120px;
		width: auto;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card w-100">
			<div class="card-body">

				@if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)
				<a href="/posicao/new" class="btn btn-success ml-1 mb-3">
					<i class="la la-plus"></i> Cadastrar posição
				</a>
				@endif
				<h1 class="h3 mb-2 text-gray-800">Posiçoes</h1>

				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">
					<div class="col-md-2">
						{!!Form::text('search', 'Pesquisar por nome')
						!!}
					</div>
					<div class="col-md-2">
						{!!Form::select('faixa_id', 'Faixa', [null => 'Selecione...'] + $faixas->pluck('nome',
						'id')->all())
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('categoria_id', 'Categoria', [null => 'Selecione...'] + $categorias->pluck('nome',
						'id')->all())
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('ordem', 'Ordar por', ['' => 'Selecione...', 'nome' => 'Nome', 'created_at' => 'Data de registro'])
						!!}
					</div>

					<div class="col-md-2 text-left mt-1">
						<br>
						<button class="btn btn-sm  btn-primary" style="font-size: 9px;" type="submit"><svg
							xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor"
							class="bi bi-funnel-fill" viewBox="0 0 16 16">
							<path
							d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
						</svg> Filtrar</button>
						<a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger"
						href="{{ route('posicao.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
						height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
						<path
						d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
					</div>
				</div>
				{!!Form::close()!!}

				<div class="row mt-2">
					@forelse($data as $posicao)
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card {{$posicao->faixa_border()}} shadow h-100">

							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('posicao.view', $posicao->id) }}">{{$posicao->nome}} </a></h6>
								@if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)

								<div class="dropdown no-arrow">
									<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"aria-labelledby="dropdownMenuLink">

										<a class="dropdown-item text-warning" href="{{ route('posicao.edit', $posicao->id) }}"><i class="la la-edit mr-2"></i>Editar</a>

										<form action="{{ route('posicao.delete', $posicao->id) }}" method="post"
											id="form-{{$posicao->id}}">
											@csrf
											<button type="button" class="dropdown-item btn-delete text-danger">
												<i class="la la-trash mr-2"></i>Remover
											</button>
										</form>

										<a class="dropdown-item text-info" href="{{ route('posicao.view', $posicao->id) }}"><i class="la la-video mr-2"></i>Ver</a>
										<a class="dropdown-item text-black" href="{{ route('posicao.push', $posicao->id) }}"><i class="la la-bell mr-2"></i>Push</a>

									</div>
								</div>
								@endif
							</div>
							<div class="card-body" onclick="ver('{{$posicao->id}}')">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Categoria: <strong>{{ $posicao->categoria->nome }}</strong> 
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Faixa: <strong>{{ $posicao->faixa->nome == 'Branca' ? "Todas" : $posicao->faixa->nome }}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data de cadastro: <strong>{{\Carbon\Carbon::parse($posicao->created_at)->format('d/m/Y H:i')}}</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Videos da posição: <strong>{{ sizeof($posicao->videos) }}</strong>
										</div>

										@if(session('user_logged')['master'])
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Acessos de alunos: <strong class="text-danger">{{ sizeof($posicao->views) }}</strong>
										</div>
										@endif

										@if($posicao->status == false)
										<h5 class="text-danger">Posição inátiva</h5>
										@endif
									</div>
									<div class="col-auto">
										@if($posicao->imagem == "")
										<img class="img-profile rounded-circle"
										src="/images/no_image.png">
										@else
										<img class="img-profile rounded-circle"
										src="/posicoes/{{$posicao->imagem}}">
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
					{{ $data->appends(request()->all())->links() }}
				</nav>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
	function ver(id){
		location.href = '/posicao/view/'+id
	}
</script>
@endsection

