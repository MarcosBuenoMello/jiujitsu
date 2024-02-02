@extends('default', ['title' => 'Alunos pendentes'])
@section('content')

<style type="text/css">
	.icon{
		height: auto;
		width: 60px;
		height: 60px;
		border-radius: 50%;
	}
</style>

<div class="row">
	<div class="col-12">


		<div class="card">
			<div class="card-header">
				<h4>Alunos pendentes</h4>
				
			</div>
			<div class="card-body table-responsive">
				<table class="table">
					<thead class="">
						<tr>
							<th></th>
							<th width="30%">Nome</th>
							<th width="20%">Data último pagamento</th>

						</tr>
					</thead>
					<tbody>
						@forelse($pendentes as $item)
						<tr>
							<td>
								@if($item->imagem == "")
								<img class="icon"
								src="/img/undraw_profile.svg">
								@else
								<img class="icon"
								src="/alunos/{{$item->imagem}}">
								@endif

							</td>

							<td>{{$item->nome}} {{$item->sobre_nome}}</td>

							@if($item->data_ultimo_pagamento != "")
							<td>{{ \Carbon\Carbon::parse($item->data_ultimo_pagamento)->format('d/m/Y') }} </td>
							@else
							<td>--</td>
							@endif
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

