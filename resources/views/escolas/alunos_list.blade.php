@extends('default', ['title' => 'Exames de faixa para alunos'])
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

				<h1 class="h3 mb-2 text-gray-800 ">Alunos cadastros com escola</h1>

				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">

					<div class="col-md-3">
						{!!Form::text('search', 'Pesquisar por aluno')
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
						href="{{ route('exame-aluno.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
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
							<thead>
								<tr>
									<td>Aluno</td>
									<td>Boletins</td>
									<td>#</td>
								</tr>
							</thead>
							<tbody>
								@foreach($data as $item)
								<tr>
									<td>{{ $item->full_name }}</td>
									<td>{{ sizeof($item->boletins) }}</td>
									<td>
										<a class="dropdown-item text-info" href="{{ route('aluno.boletin', $item->id) }}"><i class="la la-school mr-2"></i>Boletim</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	function ver(id){
		location.href = '/exames/view/'+id
	}
</script>
@endsection

