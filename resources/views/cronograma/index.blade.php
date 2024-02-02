@extends('default', ['title' => 'Cronograma de treino'])
@section('content')

<style type="text/css">
	.img-profile{
		height: 120px;
		width: auto;
	}
</style>

<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">

				<div class="row">
					<div class="col-12">
						<h1 class="h3 mb-2 mr-3 text-gray-800">Cronograma de treinos da semana</h1>
					</div>
				</div>
				<div class="col-12">
					<div class="row">

						@forelse($agenda as $a)

						<div class="col-xl-4 col-md-6 mb-4">
							<div class="card shadow h-100">
								<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">
										Treino: {{$a->modalidade->nome}}

										@if($a->sexo != 't')
										@if($a->sexo == 'f')
										Feminino
										@else
										Masculino
										@endif
										@endif
									</h6>

								</div>
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">

											<div class="h6 mb-0 font-weight-bold text-gray-800">
												Dia da semana: <strong>{{mb_strtoupper($a->dia_semana)}}</strong>
											</div>
											<div class="h6 mb-0 font-weight-bold text-gray-800">
												Data: <strong>{{\Carbon\Carbon::parse($a->date())->format('d/m/Y')}}</strong>
											</div>
											<div class="h6 mb-0 font-weight-bold text-gray-800">
												Horário: <strong>{{($a->horario)}}</strong>
											</div>
											<div class="h6 mb-0 font-weight-bold text-gray-800">
												Cidade: <strong>{{$a->cidade->nome}}</strong>
											</div>


										</div>
									</div>
								</div>
								<div class="card-footer">
									@if($a->confirmado == false)

									<button onclick="confirmarTreino('{{$a->id}}')" class="btn btn-success btn-block">
										Confirmar treino
									</button>

									@else

									@if($a->status)
									<button class="btn btn-success btn-block" disabled>
										<i class="la la-check"></i>
										Treino confirmado
									</button>

									<button onclick="desmarcarTreino('{{$a->treino_id}}')" class="btn btn-danger btn-block">
										<i class="la la-check"></i>
										Desmarcar treino
									</button>

									@if($a->descricao != "")
									<button class="btn btn-info btn-block" onclick="swal('Descrição do treino', '{{$a->descricao}}', 'info')">
										<i class="la la-comment"></i>
										Descrição
									</button>
									@endif

									@else
									<button onclick="reconfirmarTreino('{{$a->treino_id}}')" class="btn btn-success btn-block">
										Reconfirmar treino
									</button>

									@endif
									@endif

								</div>
							</div>
						</div>

						@empty
						<p class="ml-3">Nenhum registro encontrado!</p>
						@endforelse

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-confirmar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<form method="get" action="/treino/confirmarTreino">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Confirmação de treino</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<input type="hidden" id="agenda_id" name="agenda_id">
				<div class="col-md-12 mt-2">
					{!!Form::textarea('descricao', 'Descrição do treino (opcional)')
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

<div class="modal fade" id="modal-desmarcar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<form method="get" action="/treino/desmarcarTreino">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Desmarcar de treino</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<input type="hidden" id="treino_id" name="treino_id">
				<div class="col-md-12 mt-2">
					{!!Form::textarea('descricao', 'Descrição do treino (opcional)')
					->attrs(['class' => 'form-control'])
					!!}
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
					<button class="btn btn-danger" type="submit">Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modal-reconfirmar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<form method="get" action="/treino/reconfirmar">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Reconfirmar de treino</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<input type="hidden" id="treino_id2" name="treino_id">
				<div class="col-md-12 mt-2">
					{!!Form::textarea('descricao', 'Descrição do treino (opcional)')
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
	function confirmarTreino(id){
		$('#modal-confirmar').modal('show')
		$('#agenda_id').val(id)
	}

	function desmarcarTreino(treino_id){
		$('#modal-desmarcar').modal('show')
		$('#treino_id').val(treino_id)
	}

	function reconfirmarTreino(treino_id){
		$('#modal-reconfirmar').modal('show')
		$('#treino_id2').val(treino_id)
	}
</script>
@endsection

