@extends('default', ['title' => 'Histórico de graduação'])
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

				<div class="row mt-2">
					@forelse($aluno->boletins as $b)
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card h-100">

							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data: <strong>{{\Carbon\Carbon::parse($b->data)->format('d/m/Y')}}</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											<strong class="text-danger">{{ $b->observacao }}</strong>
										</div>

										<button type="button" class="btn btn-info" onclick="verimagem('{{$b->img}}')">
											Ver imagem
										</button>
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