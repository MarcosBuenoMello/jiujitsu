@extends('default', ['title' => 'Meus Pedidos'])
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
				<h4>Meus Pedidos</h4>
			</div>

			
			<div class="card-body">
				
				<a href="{{ route('pedidos.carrinhos') }}" class="btn btn-danger ml-1 mb-3 float-right">
					<i class="la la-shopping-cart"></i> Ver carrinhos de compra
				</a>

				<a href="{{ route('pedidos.consulta_pagamentos') }}" class="btn btn-info ml-1 mb-3 float-right">
					<i class="la la-refresh"></i> Consultar pagamentos
				</a>
				<div class="table-responsive">
					<table class="table">
						<thead class="">
							<tr>
								<th width="20%">Data</th>
								<th width="15%">Valor</th>
								<th width="20%">Status</th>
								<th width="20%">Forma de pagamento</th>
								<th width="20%">Observação</th>
								<th width="30%">Ação</th>
							</tr>
						</thead>
						<tbody>
							@forelse($data as $item)
							<tr>
								<td>{{ \Carbon\Carbon::parse($item->data_pagamento)->format('d/m/Y') }} </td>
								<td>R$ {{ number_format($item->valor, 2, ',', '.')}} </td>
								<td>
									@if($item->status == 'approved')

									<span class="text-success">APROVADO</span>

									@elseif($item->status == 'cancelled')
									<span class="text-danger">CANCELADO</span>

									@elseif($item->status == 'pending')
									<span class="text-warning">PENDENTE</span>

									@endif
								</td>
								<td>{{ mb_strtoupper($item->tipo_pagamento) }}</td>
								<td>{{ $item->observacao }}</td>
								<td class="row">

									<a href="{{ route('loja.detalhes_pedido', [$item]) }}" class="btn btn-info btn-sm w-100">
										Detalhes
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
	</div>
</div>

@endsection

