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
				<h4>Carrinho</h4>
			</div>

			<div class="card-body">

				<a href="/loja/finalizar" class="btn btn-success ml-1 mb-3 float-right">
					<i class="la la-check"></i> Finalizar compra
				</a>
				<a href="/loja/produtos" class="btn btn-info ml-1 mb-3 float-right">
					<i class="la la-chevron-left"></i> Continuar comprando
				</a>
				
				@if($itensRemovidos)
				<div class="alert alert-custom alert-danger fade show mt-2 w-100" role="alert">

					<div class="alert-text">Carrinho foi reajustado, alguns itens estão com estoque insuficiente no momento!</div>
					<div class="alert-close">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true"><i class="la la-close"></i></span>
						</button>
					</div>
				</div>
				@endif

				<div class="table-responsive">

					<h4>Total do pedido R$ {{ number_format($carrinho->total, 2, ',', '.') }}</h4>
					<table class="table">
						<thead class="">
							<tr>
								<th width="10%"></th>
								<th width="20%">Produto</th>
								<th width="15%">Valor</th>
								<th width="15%">Ação</th>
							</tr>
						</thead>
						<tbody>
							@forelse($carrinho->itens as $item)
							<tr>
								<td>
									<img class="icon rounded-circle"
									src="{{$item->produto->image_main}}">

								</td>

								<td>{{ $item->produto->nome }} {{ $item->produto->tamanho }}</td>
								<td>R$ {{ number_format($item->valor, 2, ',', '.')}} </td>

								<td class="row">

									<form action="{{ route('loja.delete_item', $item->id) }}" method="post"
										id="form-{{$item->id}}">
										@csrf
										@method('delete')
										<button type="button" class="btn btn-sm btn-danger btn-delete">
											<i class="la la-trash mr-2"></i>
										</button>
									</form>

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

