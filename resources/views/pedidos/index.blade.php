@extends('default', ['title' => 'Pedidos'])
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
				<h4>Pedidos</h4>
			</div>

			{!!Form::open()->fill(request()->all())
			->get()
			!!}
			<div class="row ml-3 mt-2 mr-2">
				<div class="col-md-3">
					{!!Form::text('search', 'Pesquisar por aluno')
					!!}
				</div>

				<div class="col-md-2">
					{!!Form::select('cidade_id', 'Cidade', [null => 'Selecione...'] + $cidades->pluck('nome',
					'id')->all())
					!!}
				</div>

				<div class="col-md-2">
					{!!Form::select('status', 'Status pagamento', 
					[
					'' => 'Todos', 
					'approved' => 'Aprovado', 
					'cancelled' => 'Cancelado', 
					'pending' => 'Pendente'
					])
					!!}
				</div>

				<div class="col-md-2">
					{!! Form::date('start_date', 'Dt. Inicio') !!}
				</div>
				<div class="col-md-2">
					{!! Form::date('end_date', 'Dt. Fim') !!}
				</div>


				<div class="col-md-2 text-left mt-1">
					<br>
					<button class="btn btn-sm  btn-primary" style="font-size: 9px;" type="submit"><svg
						xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor"
						class="bi bi-funnel-fill" viewBox="0 0 16 16">
						<path
						d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
					</svg> Filtrar</button>
					<a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger"
					href="{{ route('mensalidade.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
					height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
					<path
					d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
				</div>
			</div>

			<div class="col-12 mt-2"></div>
			{!!Form::close()!!}

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
								<th></th>
								<th width="20%">Aluno</th>
								<th width="15%">Valor</th>
								<th width="20%">Data</th>
								<th width="20%">Status</th>
								<th width="20%">Forma de pagamento</th>
								<th width="20%">Observação</th>
								<th width="30%">Ação</th>
							</tr>
						</thead>
						<tbody>
							@forelse($data as $item)
							<tr>
								<td>
									@if($item->aluno->imagem == "")
									<img class="icon rounded-circle"
									src="/img/undraw_profile.svg">
									@else
									<img class="icon rounded-circle"
									src="/alunos/{{$item->aluno->imagem}}">
									@endif

								</td>

								<td>{{$item->aluno->nome}} {{$item->aluno->sobre_nome}}</td>
								<td>R$ {{ number_format($item->valor, 2, ',', '.')}} </td>
								<td>{{ \Carbon\Carbon::parse($item->data_pagamento)->format('d/m/Y') }} </td>
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

									@if($item->status != 'approved')
									<form action="{{ route('pedidos.destroy', $item->id) }}" method="post"
										id="form-{{$item->id}}">
										@csrf
										@method('delete')
										<button type="button" class="btn btn-sm btn-danger btn-delete w-100">
											Remover
										</button>
									</form>
									@endif

									<a href="{{ route('pedidos.show', [$item]) }}" class="btn btn-info btn-sm w-100">
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
			<div class="col-12">
				<nav class="d-flex justify-content-end" aria-label="...">
					{{ $data->appends(request()->all())->links() }}
				</nav>
			</div>
		</div>
	</div>
</div>

@endsection

