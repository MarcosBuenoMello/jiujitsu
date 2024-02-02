@extends('default', ['title' => 'Produtos'])
@section('content')

<style type="text/css">
	.img-profile{
		height: 120px;
		width: 120px;
	}
	.rounded-circle{
		border: 1px solid #D9D9D9 !important;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card w-100">
			<div class="card-body">

				@if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)
				<a href="{{ route('produtos.create') }}" class="btn btn-success ml-1 mb-3">
					<i class="la la-plus"></i> Cadastrar produto
				</a>
				@endif
				<h1 class="h3 mb-2 text-gray-800">Produtos</h1>

				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">
					<div class="col-md-3">
						{!!Form::text('search', 'Pesquisar por nome')
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('categoria_id', 'Categoria', [null => 'Selecione...'] + $categorias->pluck('nome',
						'id')->all())
						!!}
					</div>
					<div class="col-md-2">
						{!!Form::select('status', 'Status', ['' => 'Selecione...', '1' => 'Ativo', '-1' => 'Inátivo'])
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
					@forelse($data as $produto)
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card shadow h-100">

							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">{{$produto->nome}}</h6>
								

								<div class="dropdown no-arrow">
									<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"aria-labelledby="dropdownMenuLink">

										<a class="dropdown-item text-warning" href="{{ route('produtos.edit', $produto->id) }}"><i class="la la-edit mr-2"></i>Editar</a>

										<form action="{{ route('produtos.destroy', $produto->id) }}" method="post" id="form-{{$produto->id}}">
											@csrf
											@method('delete')
											<button type="button" class="dropdown-item btn-delete text-danger">
												<i class="la la-trash mr-2"></i>Remover
											</button>
										</form>

										<a class="dropdown-item text-info" href="{{ route('produtos.galery', $produto->id) }}"><i class="la la-photo mr-2"></i>Galeria</a>

									</div>
								</div>

							</div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Categoria: <strong>{{ $produto->categoria->nome }}</strong> 
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Valor: <strong>R$ {{ number_format($produto->valor,2,',','.') }}</strong> 
										</div>
										
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data de cadastro: <strong>{{\Carbon\Carbon::parse($produto->created_at)->format('d/m/Y H:i')}}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Tamanho: <strong>{{ $produto->tamanho != '' ?  $produto->tamanho : '--'}}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Estoque: <strong>{{ $produto->estoque }}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Fotos do produto: <strong>{{ sizeof($produto->galeria) }}</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Total de acessos: <strong>{{ sizeof($produto->acessos) }}</strong>
										</div>

										@if($produto->status == false)
										<h6 class="text-danger mt-1">Produto desativado</h6>
										@endif

										@if($produto->estoque <= 0)
										<h6 class="text-danger mt-1">Produto sem estoque</h6>
										@endif
									</div>

									<div class="col-auto">
										@if(sizeof($produto->galeria) > 0)
										
										<img class="img-profile rounded-circle"
										src="{{$produto->image_main}}">
										@else
										<img class="img-profile rounded-circle"
										src="/images/no_image2.png">
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



