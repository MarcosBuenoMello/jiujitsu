@extends('default', ['title' => 'Consulta Pagamentos Loja'])
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
				<h4>Consulta Pagamentos Loja</h4>
				<h5 class="text-danger">Pedidos que registraram alterações de status de pagamento</h5>
			</div>


			<div class="card-body">
				
				<table class="table">
					<thead class="">
						<tr>
							<th width="20%">Aluno</th>
							<th width="20%">Pedido ID</th>
							<th width="20%">Transação ID</th>
							<th width="20%">Status</th>
							<th width="20%">Total</th>
							<th width="20%">Data</th>
						</tr>
					</thead>
					<tbody>
						@forelse($alteracoes as $item)
						<tr>
							<td>{{ $item['aluno'] }}</td>
							<td>{{ $item['pedido_id'] }}</td>
							<td>{{ $item['transacao_id'] }}</td>
							<td>
								@if($item['status'] == 'approved')

								<span class="text-success">APROVADO</span>

								@elseif($item['status'] == 'cancelled')
								<span class="text-danger">CANCELADO</span>

								@elseif($item['status'] == 'pending')
								<span class="text-warning">PENDENTE</span>

								@endif
							</td>
							<td>{{\Carbon\Carbon::parse($item['data'])->format('d/m/Y H:i')}}</td>
							<td>{{ number_format($item['total'], 2, ',', '.') }}</td>
							
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

