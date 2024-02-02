@extends('default', ['title' => 'Cadastrar posição'])
@section('content')

<div class="row">
	<div class="card">
		<div class="card-body">
			<div class="row align-items-center">
				<div class="col-md-8">
					<h3 class="mb-0">Cadastrar posição</h3>
				</div>
				<div class="col-md-4 text-right">
					<a href="/posicao" class="btn btn-sm btn-primary">Voltar</a>
				</div>
			</div>
		</div>
		<div class="card-body">

			{!!Form::open()
			->post()
			->id('form-posicao')
			->autocomplete('off')
			->route('posicao.store')
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

	$().ready(function(){ 
		isSelecionado()
	})
	$('#inp-upload_link').click(() => {
		isSelecionado()
	})

	function isSelecionado(){
		let selecionado = $('#inp-upload_link').is(':checked')
		if(selecionado){
			$('.tp-link').css('display', 'inline-block')
			$('.tp-link-hidden').css('display', 'none')
		}else{
			$('.tp-link').css('display', 'none')
			$('.tp-link-hidden').css('display', 'inline-block')
		}
	}

	$('#inp-link').keyup(() => {
		validaLink()
	})

	$('#inp-link').blur(() => {
		validaLink()
	})

	function validaLink(){

		let link = $('#inp-link').val()
		if(link.length > 0){

			if(link.includes('drive')){
				let sp = link.split('/')
				$('#inp-link').val(sp[5])
				$('#inp-tipo').val('google_drive').change()
			}else if(link.includes('youtube')){
				console.log(link)
				let sp = link.split('v=')
				$('#inp-link').val(sp[1])
				$('#inp-tipo').val('youtube').change()

			}else if(link.includes('youtu.be')){
				let sp = link.split('/')
				console.log(sp)
				$('#inp-link').val(sp[3])
				$('#inp-tipo').val('youtube').change()

			}
		}
	}
</script>

@endsection