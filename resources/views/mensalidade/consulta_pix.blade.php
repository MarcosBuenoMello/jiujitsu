@extends('default', ['title' => 'Mensalidades'])
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
				<h4>Consulta PIX</h4>
			</div>


			<div class="card-body">
				
				<table class="table">
					<thead class="">
						<tr>
							<th width="20%">Nome</th>
							<th width="20%">Data</th>
							<th width="20%">Valor</th>
							<th width="20%">Status</th>
							<th width="30%">Ação</th>
						</tr>
					</thead>
					<tbody>
						@forelse($modificado as $item)
						<tr>
							<td>{{$item->nome}} {{$item->sobre_nome}}</td>
							<td>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i')}}</td>
							<td>{{ number_format($item->valor, 2, ',', '.') }}</td>
							<td>
								@if($item->status == 'approved')

								<span class="text-success">APROVADO</span>

								@elseif($item->status == 'cancelled')
								<span class="text-danger">CANCELADO</span>

								@elseif($item->status == 'pending')
								<span class="text-warning">PENDENTE</span>

								@endif
							</td>

							<td class="row">

								<a class="btn btn-success" href="{{ route('mensalidade.setPlano', $item->id) }}">
									<i class="la la-check"></i>
								</a>


								<form action="{{ route('mensalidade.deleteCheckout', $item->id) }}" method="post"
									id="form-{{$item->id}}">
									@csrf
									<button type="button" class="btn btn-danger btn-delete ml-1">
										<i class="la la-trash"></i>

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

@endsection

