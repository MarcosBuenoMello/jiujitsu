@extends('default', ['title' => 'Retirada'])
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
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Retirada</h3>
					</div>
				</div>


			</div>
			<div class="card-body">


				{!!Form::open()
				->post()
				->autocomplete('off')
				->route('contribuicao.retirar-store')
				->multipart()!!}
				<div class="pl-lg-4">

					<div class="row">
						<div class="col-md-2">
							{!!Form::text('valor', 'Valor')
							->attrs(['class' => 'money'])
							!!}
						</div>

						<div class="col-md-10">
							{!!Form::text('motivo', 'Motivo')
							!!}
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
						</div>
					</div>

				</div>
				{!!Form::close()!!}

				<div class="col-12 mt-4">
					<div class="table-responsive">
						<table class="table">
							<thead class="">
								<tr>

									<th width="20%">Valor</th>
									<th width="70%">Motivo</th>
									<th width="10%">Ação</th>
								</tr>
							</thead>
							<tbody>
								@forelse($data as $item)
								<tr>
									<td>{{ number_format($item->valor, 2, ',', '.') }}</td>
									<td>{{ $item->motivo }}</td>
									<td>
										<form action="{{ route('contribuicaoRetirar.delete', $item->id) }}" method="post"
											id="form-{{$item->id}}">
											@csrf
											@method('delete')
											<button type="button" class="btn btn-delete btn-danger">
												<i class="la la-trash mr-2"></i>
											</button>
										</form>
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="3" class="ml-3">Nenhum registro encontrado!</td>
								</tr>
								@endforelse
							</tbody>
							<caption>Registros de retirada</caption>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection


