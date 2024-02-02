@extends('default', ['title' => 'Editar posição'])
@section('content')

<div class="row">
	<div class="card">
		<div class="card-body">
			<div class="row align-items-center">
				<div class="col-md-8">
					<h3 class="mb-0">Editar posição</h3>
				</div>
				<div class="col-md-4 text-right">
					<a href="/posicao" class="btn btn-sm btn-primary">Voltar</a>
				</div>
			</div>
		</div>
		<div class="card-body">

			{!!Form::open()->fill($item)
			->put()
			->id('form-posicao')
			->autocomplete('off')
			->route('posicao.update', [$item->id])
			->multipart()!!}
			<div class="pl-lg-4">
				@include('posicao._forms')
			</div>
			{!!Form::close()!!}

		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('#btn-salvar').click(function() {
		$('.spinner-border').css('display', 'inline-block')
		$('#btn-salvar').attr('disabled', 'disabled')

		$('#form-posicao').submit()
	});
</script>

@endsection