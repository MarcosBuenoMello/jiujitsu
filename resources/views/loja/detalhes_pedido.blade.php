@extends('default', ['title' => 'Carrinho'])
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
				<h4>Pedido: <strong>{{$pedido->id}}</strong></h4>
				<h4>Status: 
					@if($pedido->status == 'approved')

					<span class="text-success">APROVADO</span>

					@elseif($pedido->status == 'cancelled')
					<span class="text-danger">CANCELADO</span>

					@elseif($pedido	->status == 'pending')
					<span class="text-warning">PENDENTE</span>

					@endif
				</h4>
			</div>

			<div class="card-body">

				<h5>Data do pedido: <strong>{{ \Carbon\Carbon::parse($pedido->updated_at)->format('d/m/Y H:i')}}</strong></h5>

				<hr>
				<div class="table-responsive">

					<h6>Total do pedido R$ {{ number_format($pedido->total, 2, ',', '.') }}</h6>
					<table class="table">
						<thead class="">
							<tr>
								<th width="10%"></th>
								<th width="20%">Produto</th>
								<th width="15%">Valor</th>
							</tr>
						</thead>
						<tbody>
							@forelse($pedido->itens as $item)
							<tr>
								<td>
									<img class="icon rounded-circle"
									src="{{$item->produto->image_main}}">

								</td>

								<td>{{ $item->produto->nome }} {{ $item->produto->tamanho }}</td>
								<td>R$ {{ number_format($item->valor, 2, ',', '.')}} </td>

								
							</tr>

							@empty
							<tr>
								<td colspan="5" class="ml-3">Nenhum registro encontrado!</td>
							</tr>
							@endforelse
						</tbody>
						<caption>Produtos do pedido</caption>
					</table>
				</div>
			</div>
			
		</div>
	</div>
</div>

@endsection

