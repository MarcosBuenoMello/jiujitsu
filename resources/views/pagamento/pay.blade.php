@extends('default', ['title' => 'Pagamento de mensalidade'])
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
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Pagamento de mensalidade</h3>
					</div>
				</div>


			</div>
			<div class="card-body">

				<h4 class="mb-2">Valor da mensalidade <strong class="text-primary"> R$ {{ number_format($valor_mensalidade, 2, ',', '.')}}</strong></h4>

				{!!Form::open()
				->post()
				->autocomplete('off')
				->route('pagamento.pay')
				->multipart()!!}
				<div class="">
					@include('pagamento._forms')
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

<script type="text/javascript">
	$(function () {

		window.Mercadopago.setPublishableKey('{{getenv("MERCADOPAGO_PUBLIC_KEY_PRODUCAO")}}');
		window.Mercadopago.getIdentificationTypes();

		setTimeout(() => {
			$('#docNumber').mask('000.000.000-00', {reverse: true});
		}, 2000)
	});

	$('#docType').change(() => {
		let tp = $('#docType').val()
		if(tp == 'CNPJ'){
			$('#docNumber').mask('00.000.000/0000-00', {reverse: true});
		}else{
			$('#docNumber').mask('000.000.000-00', {reverse: true});
		}
	})
</script>
@endsection

