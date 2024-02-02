@extends('default', ['title' => 'Histórico de boletim'])
@section('content')


<style type="text/css">
	.img-profile{
		height: auto;
		width: 170px;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card w-100">
			<div class="card-body">

				<h1 class="h3 mb-2 text-gray-800">Histórico de boletim - <strong>{{$aluno->full_name}}</strong></h1>

				<a data-toggle="modal" data-target="#modal-boletin" class="btn btn-success ml-1 mb-3 float-right">
					<i class="la la-plus"></i> Adicionar boletim
				</a>
				<div class="row mt-2 col-12">
					@forelse($aluno->boletins as $g)
					<div class="col-xl-6 col-12">
						<div class="card h-100">

							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data: <strong>{{\Carbon\Carbon::parse($g->data)->format('d/m/Y')}}</strong>
										</div>


										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Observação: <strong>{{ $g->observacao }}</strong>
										</div>

										<form class="mt-2" action="{{ route('aluno.deleteBoletin', $g->id) }}" method="post"
											id="form-{{$g->id}}">
											@csrf
											@method('delete')
											<button type="button" class="btn btn-delete btn-danger">
												Remover
											</button>

											<button type="button" class="btn btn-info" onclick="verimagem('{{$g->img}}')">
												Ver imagem
											</button>
										</form>
										
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

<div class="modal fade" id="modal-boletin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<form method="post" enctype="multipart/form-data" action="/aluno/boletinStore">
			@csrf
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Adionando Boletim - Escola {{$aluno->escola->nome}}</h5>

				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					{!!Form::textarea('observacao', 'Observação')
					!!}
				</div>
				<input type="hidden" name="aluno_id" value="{{$aluno->id}}">
				<div class="col-md-4">
					<input type="file" name="file" class="file" accept="image/*">
					<div class="input-group my-3">
						<input type="text" class="form-control mt-3" disabled placeholder="Foto" id="file">
						<div class="input-group-append">
							<button type="button" class="browse btn btn-primary mt-3">Procurar...</button>
						</div>

					</div>

				</div>

			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" type="button" data-dismiss="modal">Fechar</button>
				<button type="subimt" class="btn btn-success">Salvar</button>
			</div>
		</form>
	</div>
</div>
</div>

<div class="modal fade" id="modal-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">

			<div class="modal-header">

				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<img src="" style="width: 100%; margin-left: auto;" id="img-modal">

			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" type="button" data-dismiss="modal">Fechar</button>

			</div>

		</div>
	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
	function verimagem(img){
		$('#img-modal').attr('src', '/boletins/'+img)
		$('#modal-img').modal('show')
	}
</script>
@endsection