@extends('default', ['title' => 'Avisos'])
@section('content')

<style type="text/css">
	img{
		height: auto;
		width: 100%;
		border-radius: 5%;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>{{$item->titulo}}</h4>

			</div>
			<div class="card-body">
				<p>{{$item->texto}}</p>

				@if($item->imagem != "")
				<img src="/avisos/{{$item->imagem}}">
				@endif

				@if(session('user_logged')['master'])
				<button data-toggle="modal" data-target="#modal-views" class="btn btn-primary mt-1">
					<i class="la la-eye"></i>
					Visualizações
				</button>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-views" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alunos que visualizaram</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="table-responsive">

						<table class="table">
							<thead class="">
								<tr>

									<th width="100%">Aluno</th>
								</tr>
							</thead>
							<tbody>
								@foreach($item->views as $v)
								<tr>
									<td>{{$v->aluno->full_name}}</td>
								</tr>
								@endforeach
							</tbody>

						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Fechar</button>

			</div>
		</div>
		{!!Form::close()!!}

	</div>
</div>

@endsection

