@extends('default', ['title' => 'Avisos'])
@section('content')

<style type="text/css">
	.icon{
		height: 50px;
		width: 50px;
		border-radius: 10%;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<a href="/aviso/new" class="btn btn-success ml-1 mb-3">
					<i class="la la-plus"></i> Cadastrar aviso
				</a>
				<h1 class="h3 mb-2 text-gray-800">Avisos</h1>

				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">
					<div class="col-md-3">
						{!!Form::text('search', 'Pesquisar por titulo')
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
						href="{{ route('categoria.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
						height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
						<path
						d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
					</div>
				</div>

				<div class="col-12 mt-2"></div>
				{!!Form::close()!!}

				<div class="row">
					<div class="table-responsive">

						<table class="table">
							<thead class="">
								<tr>
									<th></th>
									<th width="50%">Título</th>
									<th width="20%">Data</th>
									<th width="20%">Visualizações</th>
									<th width="20%">Ação</th>
								</tr>
							</thead>
							<tbody>
								@forelse($data as $item)
								<tr>
									<td>
										@if($item->imagem == "")
										<img class="icon rounded-circle"
										src="/images/no_image2.png">
										@else
										<img class="icon rounded-circle"
										src="/avisos/{{$item->imagem}}">
										@endif

									</td>
									<td>{{ $item->titulo }}</td>
									<td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
									<td>{{ sizeof($item->views) }}</td>
									<td class="row">

										<form action="{{ route('aviso.delete', $item->id) }}" method="post"
											id="form-{{$item->id}}">
											@csrf
											<button type="button" class="btn btn-sm btn-danger btn-delete">
												<i class="la la-trash mr-2"></i>
											</button>
										</form>
										<a class="btn btn-sm btn-warning" href="{{ route('aviso.edit', $item->id)}}">
											<i class="la la-edit mr-2"></i>
										</a>

										<a class="btn btn-sm btn-info" href="{{ route('aviso.view', $item->id)}}">
											<i class="la la-binoculars mr-2"></i>
										</a>
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="5" class="ml-3">Nenhum registro encontrado!</td>
								</tr>
								@endforelse
							</tbody>
						</table>

					</div>
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

