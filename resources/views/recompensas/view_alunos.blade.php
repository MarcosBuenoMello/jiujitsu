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
				
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead class="">
							<tr>
								<th></th>
								<th>Faixa</th>
								<th>Qtd. presenças</th>
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

