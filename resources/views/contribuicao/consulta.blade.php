@extends('default', ['title' => 'Consulta Contribuição'])
@section('content')

<style type="text/css">
	.icon{
		height: 50px !important;
		width: 50px !important;
		border-radius: 10%;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4>Consulta Contribuição</h4>
			</div>

			<div class="card-body">
				
				<table class="table">
					<thead class="">
						<tr>
							<th width="20%">Aluno</th>
							<th width="20%">Data</th>
							<th width="20%">Trasação ID</th>
							<th width="20%">Status</th>
						</tr>
					</thead>
					<tbody>
						@forelse($modificado as $item)
						<tr>
							<td>{{ $item->full_name }}</td>
							<td>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i')}}</td>
							<td>{{ $item->transacao_id }}</td>
							<td>
								@if($item->status == 'approved')

								<span class="text-success">APROVADO</span>

								@elseif($item->status == 'cancelled')
								<span class="text-danger">CANCELADO</span>

								@elseif($item->status == 'pending')
								<span class="text-warning">PENDENTE</span>

								@endif
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
</div>

@endsection

