@extends('default', ['title' => 'Visualizando exame'])
@section('content')

<div class="col-lg-12">

	<div class="card shadow mb-4">
		
		<div class="card-body">
			<div class="row">

				<div class="row">
					<div class="col-lg-12">
						<h4 class="ml-3">Aluno: <strong>{{$item->aluno->full_name}}</strong></h4>
						<h4 class="ml-3">Faixa: <strong>{{$item->exame->faixa->nome}}</strong></h4>
						<h5 class="ml-3">Descrição: <strong>{{$item->observacao == "" ? "--" : $item->observacao}}</strong></h5>
						
					</div>


				</div>
				<div class="col-lg-12">

					<div class="table-responsive">
						<table class="table" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>Posição</th>
									<th>Categoria</th>
									<th>Status</th>
									<th>#</th>
								</tr>
							</thead>

							<tbody>
								@foreach($item->posicoes as $key => $p)

								<tr @if($p->status) class="bg-success text-white" @endif>
									<td>{{ $p->posicao->nome }}</td>
									<td>{{ $p->posicao->categoria->nome }}</td>
									@if($p->status)
									<td><label class="@if($p->status) text-white @endif">OK</label></td>
									@else
									<td><label class="text-danger">Pendente</label></td>
									@endif
									<td>
									@if($p->status)
									<a class="btn btn-danger" href="/exames-aluno/alterarStatus/{{$p->id}}">
										Pendente
									</a>
									@else
									<a class="btn btn-success" href="/exames-aluno/alterarStatus/{{$p->id}}">
										OK
									</a>
									@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<a class="btn btn-primary" href="/exames-aluno/finalizar/{{$item->id}}">Finalizar exame</a>
				</div>

			</div>
		</div>
	</div>
</div>


@endsection

@section('js')
<script type="text/javascript">
	
</script>

@endsection
