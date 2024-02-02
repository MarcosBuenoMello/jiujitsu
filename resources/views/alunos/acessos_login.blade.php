@extends('default', ['title' => 'Detalhes'])
@section('content')
<style type="text/css">
	.square{
		border: 1px solid #000;
		padding: 5px;
		border-radius: 5px;
	}

	.check{
		background: #17A673;
		border: 1px solid #17A673;

	}
</style>
<div class="row">
	<div class="col-xl-12 order-xl-1">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h5 class="mb-0">Acessos do aluno(a) <strong>{{$item->nome}} {{$item->sobre_nome}}</strong></h5>
					</div>
					<div class="col-md-4 text-right">
						<a href="/aluno" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="card">
					<div class="card-header">
						<h4>Ultimos acessos ao sistema</h4>
					</div>
					<div class="card-body">
						<table class="table">
							
							<thead>
								<tr>
									<th>Data</th>
									<th>IP</th>
								</tr>
							</thead>

							<tbody>
								@foreach($item->todosAcessos as $a)
								<tr>
									<td>{{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i') }}</td>
									<td>{{ $a->ip }}</td>
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