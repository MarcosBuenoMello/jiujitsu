@extends('default', ['title' => 'Exames de faixa para alunos'])
@section('content')
<style type="text/css">
	.img-profile{
		height: 120px;
		width: auto;
	}
</style>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<h1 class="h3 mb-2 text-gray-800 ">Exames de faixa para alunos</h1>

				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">

					<div class="col-md-3">
						{!!Form::text('search', 'Pesquisar por aluno')
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('resultado', 'Resultado', [null=> 'Todos', 'aprovado' => 'Aprovado', 'reprovado' => 'Reprovado'])
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('staus', 'Staus', [null=> 'Todos', '0' => 'Pendente', '1' => 'Finalziado'])
						!!}
					</div>

					<div class="col-md-3 text-left mt-1">
						<br>
						<button class="btn btn-sm  btn-primary" style="font-size: 9px;" type="submit"><svg
							xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor"
							class="bi bi-funnel-fill" viewBox="0 0 16 16">
							<path
							d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
						</svg> Filtrar</button>
						<a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger"
						href="{{ route('exame-aluno.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
						height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
						<path
						d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
					</div>
				</div>

				<div class="col-12 mt-2"></div>
				{!!Form::close()!!}
				<div class="row">

					@forelse($data as $e)

					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card shadow h-100">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">
									{{$e->aluno->full_name}}
								</h6>
								<div class="dropdown no-arrow">
									<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"aria-labelledby="dropdownMenuLink">


										<form action="{{ route('exame-aluno.delete', $e->id) }}" method="post"
											id="form-{{$e->id}}">
											@csrf
											<button type="button" class="dropdown-item btn-delete text-danger">
												<i class="la la-trash mr-2"></i>Remover
											</button>
										</form>
										@if(!$e->status)
										<a class="dropdown-item text-primary" href="{{ route('exame-aluno.start', $e->id) }}"><i class="la la-star mr-2"></i>Realizar exame</a>
										@endif

									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data de cadastro: <strong>{{\Carbon\Carbon::parse($e->created_at)->format('d/m/Y H:i')}}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Observação: <strong>{{$e->observacao}}</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Total de posições: <strong>{{ sizeof($e->exame->posicoes) }}</strong>
										</div>

										@if($e->status)
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											<span class="text-info">Exame finalizado</span>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Resultado: 
											@if($e->resultado)
											<strong class="text-success">Aprovado</strong>
											@else
											<strong class="text-danger">Reprovado</strong>
											@endif
										</div>
										@endif
									</div>

								</div>
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
@endsection

@section('js')
<script type="text/javascript">
	function ver(id){
		location.href = '/exames/view/'+id
	}
</script>
@endsection

