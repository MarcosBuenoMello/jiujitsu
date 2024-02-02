@extends('default', ['title' => 'Contribuição'])
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
				<h4>Contribuições</h4>
			</div>


			@if(session('user_logged')['master'])
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
					href="{{ route('contribuicao.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
					height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
					<path
					d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
				</div>
			</div>

			<div class="col-12 mt-2"></div>
			{!!Form::close()!!}
			@endif



			<div class="card-body">
				<a href="{{ route('contribuicao.create') }}" class="btn btn-success ml-1 mb-3">
					<i class="la la-hand-holding-usd"></i> Contribuir
				</a>

				@if(session('user_logged')['master'])
				<a href="{{ route('contribuicao.retirar') }}" class="btn btn-warning ml-1 mb-3">
					<i class="la la-minus"></i> Retirar valor
				</a>

				<a href="{{ route('contribuicao.payments_consult') }}" class="btn btn-info ml-1 mb-3 float-right">
					<i class="la la-refresh"></i> Consultar pagamentos
				</a>

				<a href="{{ route('contribuicao.pendentes') }}" class="btn btn-danger ml-1 mb-3 float-right">
					<i class="la la-close"></i> Ver pendentes
				</a>
				@endif

				<h5>Total contribuído: <strong class="text-info">R$ {{number_format($somaTotal, 2, ',', '.')}}</strong></h5>

				<h5>Valor sacado: <strong class="text-danger">R$ {{number_format($sumRetirada, 2, ',', '.')}}</strong></h5>

				<h5>Total liquído: <strong class="text-success">R$ {{number_format(($somaTotal - ($somaTotal*0.01))-$sumRetirada, 2, ',', '.')}}</strong></h5>

				<p class="text-danger">A plataforma cobra uma taxa de 1% por requisição!</p>
				
				<div class="table-responsive">
					<table class="table">
						<thead class="">
							<tr>
								<th width="15%"></th>
								<th width="30%">Aluno</th>
								<th width="20%">Data</th>
								<th width="15%">Valor</th>
							</tr>
						</thead>
						<tbody>
							@php
							$somaFiltro = 0;
							@endphp
							@forelse($data as $item)
							<tr>
								<td>
									@if($item->aluno->imagem == "")
									<img loading="lazy" class="icon rounded-circle"
									src="/img/undraw_profile.svg">
									@else
									<img loading="lazy" class="icon rounded-circle"
									src="/alunos/{{$item->aluno->imagem}}">
									@endif

								</td>

								<td>{{ $item->aluno->full_name }}</td>
								<td>
									{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }} 
									@if(session('user_logged')['master'])
									<button onclick="editarData('{{\Carbon\Carbon::parse($item->created_at)->format('Y-m-d')}}', '{{$item->id}}')" data-toggle="modal" data-target="#modal-data" class="btn btn-danger">
										Editar
									</button>
									@endif
								</td>
								<td>R$ {{ number_format($item->valor, 2, ',', '.')}} </td>
								
							</tr>
							@php
							$somaFiltro += $item->valor;
							@endphp
							@empty
							<tr>
								<td colspan="5" class="ml-3">Nenhum registro encontrado!</td>
							</tr>
							@endforelse
						</tbody>
					</table>

					@if(session('user_logged')['master'])
					<h4>Soma por filtro: <strong class="text-primary">R$ {{number_format($somaFiltro, 2, ',', '.')}}</strong></h4>
					@endif
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

<div class="modal fade" id="modal-data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<form method="post" action="{{ route('contribuicao.editData')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Alterar data</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<input type="hidden" id="contribuicao_id" name="contribuicao_id">
				<div class="col-md-12 mt-2">
					{!!Form::date('data', 'Nova data')
					->attrs(['class' => 'form-control'])
					!!}
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
					<button class="btn btn-success" type="submit">Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
	function editarData(data, id){

		$('#contribuicao_id').val(id)
		$('#inp-data').val(data)
	}
</script>
@endsection

