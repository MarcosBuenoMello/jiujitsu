@extends('default', ['title' => 'Categorias'])
@section('content')

<style type="text/css">
	.img-profile{
		height: 120px;
		width: auto;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<a href="{{ route('categoria-produtos.create') }}" class="btn btn-success ml-1 mb-3">
					<i class="la la-plus"></i> Cadastrar categoria
				</a>
				<h1 class="h3 mb-2 text-gray-800">Categorias de produto</h1>


				<div class="row">
					@forelse($categorias as $categoria)
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card shadow h-100">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">{{$categoria->nome}}</h6>
								<div class="dropdown no-arrow">
									<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"aria-labelledby="dropdownMenuLink">

										<a class="dropdown-item text-warning" href="{{ route('categoria-produtos.edit', $categoria->id) }}"><i class="la la-edit mr-2"></i>Editar</a>

										<form action="{{ route('categoria-produtos.destroy', $categoria->id) }}" method="post"
											id="form-{{$categoria->id}}">
											@csrf
											<button type="button" class="dropdown-item btn-delete text-danger">
												<i class="la la-trash mr-2"></i>Remover
											</button>
										</form>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Total de produtos cadastrados: 
											<strong>{{sizeof($categoria->produtos)}}</strong>
										</div>

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
					{{ $categorias->appends(request()->all())->links() }}
				</nav>
			</div>
		</div>
	</div>
</div>

@endsection

