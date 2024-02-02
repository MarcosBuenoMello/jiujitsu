@extends('default', ['title' => 'Presença de alunos'])
@section('content')
<div class="row">
	<div class="col-lg-12">


		<h4 class="mt-2 ml-3">Treino(s)</h4>

		<a href="/presenca/manual" class="btn btn-info ml-2 mb-3">
			<i class="la la-info"></i> Lançar presença manual
		</a>

		{!!Form::open()->fill(request()->all())
		->get()
		!!}
		<div class="row ml-3 mt-2 mr-2">

			<div class="col-md-2">
				{!!Form::select('cidade_id', 'Cidade', [null => 'Selecione...'] + $cidades->pluck('nome',
				'id')->all())
				!!}
			</div>

			<div class="col-md-2">
				{!!Form::select('dia_semana', 'Dia da semana', \App\Models\Agenda::diasDaSemana())
				!!}
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
		<div class="row">

			@forelse($treinos as $t)

			@if($t->status)
			<div class="col-xl-4 col-md-6 mb-4">
				<div class="card shadow h-100">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">
							@php
							$a = $t->agenda;
							@endphp
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
									Horário: <strong>{{($a->horario)}}</strong>
								</div>
								<div class="h6 mb-0 font-weight-bold text-gray-800">
									Cidade: <strong>{{$a->cidade->nome}}</strong>
								</div>

								@if($a->status == false)
								<h5 class="text-danger">Treino inátivo</h5>
								@endif
							</div>
						</div>
					</div>
					<div class="card-footer">

						<a class="btn btn-warning btn-block" href="/presenca/alunos/{{$t->id}}">
							<i class="la la-user-graduate"></i>
							Confirmar presença(s)
						</a>

						<a class="btn btn-success btn-block" href="/presenca/alunosPresentes/{{$t->id}}">
							<i class="la la-check"></i>
							Ver aluno(s) que estavam presentes
						</a>

					</div>
				</div>
			</div>
			@endif
			@empty
			<p class="ml-3">Nenhum registro encontrado!</p>

			@endforelse

		</div>
	</div>
</div>
@endsection

