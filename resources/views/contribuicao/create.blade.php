@extends('default', ['title' => 'Pagamento de contribuição'])
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
						<h3 class="mb-0">Contribuição</h3>
					</div>
				</div>
			</div>
			<div class="card-body">

				@if($ultimaContrib != null)
				<span class="text-danger">Data da ultima contribuição <strong>{{ \Carbon\Carbon::parse($ultimaContrib->created_at)->format('d/m/Y H:i')}}</strong></span>

				@endif

				@if($mesDeContrib != '')
				<p>Contribuição do mês de <strong class="text-success">{{$mesDeContrib}}</strong></p>
				@endif

				<h4 class="">Valor <strong class="text-primary"> R$ {{ number_format($valor_contribuicao, 2, ',', '.')}}</strong></h4>

				{!!Form::open()
				->post()
				->autocomplete('off')
				->route('contribuicao.store')
				->multipart()!!}
				<div class="">
					@include('contribuicao._forms')
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

		window.Mercadopago.setPublishableKey('{{getenv("MERCADOPAGO_PUBLIC_KEY_CONTRIB")}}');
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

