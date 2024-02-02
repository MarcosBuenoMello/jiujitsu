@extends('default', ['title' => 'Recompensas'])
@section('content')

<style type="text/css">
	.icon{
		height: 30px;
		width: 100px;
		border-radius: 10%;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>Grade de recompensas</h4>
				<a href="/recompensas/new" class="btn btn-success ml-1 mb-3 float-right">
					<i class="la la-plus"></i> Cadastrar recompensa
				</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead class="">
							<tr>
								<th></th>
								<th width="30%">Faixa</th>
								<th width="15%">Qtd. presenças</th>
								<th width="50%">Descrição</th>
								<th width="15%">Ação</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data as $item)
							<tr>
								<td>
									<img class="icon" src="/faixas/{{strtolower($item->faixa->nome)}}_{{$item->grau}}.png">
								</td>
								<td>{{$item->faixa->nome}} {{$item->grau != 0 ? $item->grau . "º grau" : ''}}</td>
								<td>{{$item->total_presencas}}</td>
								<td>{{$item->descricao}}</td>
								<td>
									<a class="btn btn-warning btm-sm" href="{{ route('recompensa.edit', $item->id) }}"><i class="la la-edit mr-2"></i></a>


									<form action="{{ route('recompensa.delete', $item->id) }}" method="post"
										id="form-{{$item->id}}">
										@csrf
										<button type="button" class="btn btn-danger btn-delete">
											<i class="la la-trash mr-2"></i>
										</button>
									</form>

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

@endsection

