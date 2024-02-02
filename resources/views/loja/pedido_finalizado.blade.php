@extends('default', ['title' => 'Pedido finalizado'])
@section('content')

<style type="text/css">
	.img-profile{
		height: 120px;
		width: auto;
	}

	.active-btn{
		border: 1px solid #4E73DF !important;
		color: #4E73DF !important;
	}

	.icon{
		height: 50px !important;
		width: 50px !important;
		border-radius: 10%;
	}

	.card-stretch:hover{
		cursor: pointer;
	}

	.input-group-append:hover{
		cursor: pointer;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Pedido finalizado</h3>
					</div>
				</div>


			</div>
			<div class="card-body">

				<input type="hidden" id="transacao_id" value="{{$pedido->transacao_id}}">
				<input type="hidden" id="tipo_pagamento" value="{{$pedido->tipo_pagamento}}">

				<h4>Total do pedido <strong class="text-success">R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong></h4>

				<table class="table">
					<thead class="">
						<tr>
							<th width="10%"></th>
							<th width="20%">Produto</th>
							<th width="15%">Valor</th>

						</tr>
					</thead>
					<tbody>
						@forelse($pedido->itens as $item)
						<tr>
							<td>
								<img class="icon rounded-circle"
								src="{{$item->produto->image_main}}">

							</td>

							<td>{{ $item->produto->nome }} {{ $item->produto->tamanho }}</td>
							<td>R$ {{ number_format($item->valor, 2, ',', '.')}} </td>

						</tr>

						@empty
						<tr>
							<td colspan="5" class="ml-3">Nenhum registro encontrado!</td>
						</tr>
						@endforelse
					</tbody>
					<caption>Produtos do pedido</caption>
				</table>

				<div class="mt-4">

					@if($pedido->tipo_pagamento == 'boleto')
					<a target="_blank" href="{{$pedido->link_boleto}}" class="btn btn-success">
						<i class="la la-print"></i>
						Imprimir Boleto
					</a>
					@endif

					@if($pedido->tipo_pagamento == 'pix')

					<h3 style="display: none" class="text-success status">Pagamento aprovado <i class="la la-check"></i></h3>

					<div class="col-lg-4 offset-lg-4">
						<img style="width: 300px; height: 300px;" src="data:image/jpeg;base64,{{$pedido->qr_code_base64}}"/>
					</div>	

					<div class="col-lg-12">

						<div class="col-lg-11 offset-lg-1">

							<div class="input-group">
								<input type="text" class="form-control" value="{{$pedido->qr_code}}" id="qrcode_input" />

								<div class="input-group-append">
									<span class="input-group-text">

										<i onclick="copy()" class="la la-copy">
										</i>

									</span>
								</div>
							</div>

						</div>				
					</div>	
					@endif

					
				</div>


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
			$('#docNumber2').mask('000.000.000-00', {reverse: true});
			$('#docNumber3').mask('000.000.000-00', {reverse: true});

			let s = $('#docType').html()
			$('#docType2').html(s)
			$('#docType3').html(s)
		}, 2000)
	});


	var status = 0
	function copy(){
		const inputTest = document.querySelector("#qrcode_input");

		inputTest.select();
		document.execCommand('copy');

		swal("", "Código pix copado!!", "success")
	}

	setInterval(() => {
		let tipo_pagamento = $('#tipo_pagamento').val();

		if(status == 0 && tipo_pagamento == 'pix'){
			let transacao_id = $('#transacao_id').val();
			$.get('/api/checkout_loja/'+transacao_id)
			.done((success) => {
				if(success == "approved"){
					swal("Sucesso", "Pagamento aprovado", "success")
					status = 1
					$('.status').css('display', 'block')

				}
			})
			.fail((err) => {
				console.log(err)
			})
		}
	}, 1000)
</script>
@endsection

