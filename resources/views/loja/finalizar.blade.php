@extends('default', ['title' => 'Finalizar compra'])
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

	.card-band{
		margin-top: 10px;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Finalizar compra</h3>
					</div>
				</div>


			</div>
			<div class="card-body">

				<h4 class="mb-2">Valor <strong class="text-primary"> R$ 
					{{ number_format($carrinho->total, 2, ',', '.') }}</strong></h4>

					<div class="mt-4">

						<div class="row">
							<div class="col-md-4 col-12">
								<button onclick="tipoPag('pix')" class="btn w-100 btn-pix active-btn" type="button" data-toggle="collapse" aria-expanded="false" data-target="#" aria-controls="">Pix</button>
							</div>
							<div class="col-md-4 col-12">
								<button onclick="tipoPag('cartao')" class="btn w-100 btn-cartao" type="button" data-toggle="collapse" aria-expanded="false" aria-controls="">Cartão de crédito</button>
							</div>
							<div class="col-md-4 col-12">
								<button onclick="tipoPag('boleto')" class="btn btn-boleto w-100" type="button" data-toggle="collapse" aria-expanded="false" aria-controls="">Boleto</button>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col">
								<div class="collapse show" id="pixCollapse">
									<div class="card card-body">
										@include('loja._forms_pix')
									</div>
								</div>
								<div class="collapse" id="cartaoCollapse">
									<div class="card card-body">
										@include('loja._forms_cartao')
									</div>
								</div>

								<div class="collapse" id="boletoCollapse">
									<div class="card card-body">
										@include('loja._forms_boleto')
									</div>
								</div>
							</div>
							
						</div>
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
			// window.Mercadopago.setPublishableKey('{{getenv("MERCADOPAGO_PUBLIC_KEY")}}');
			window.Mercadopago.getIdentificationTypes();

			setTimeout(() => {

				$('#docNumber').mask('000.000.000-00', {reverse: true});
				$('#docNumberCartao').mask('000.000.000-00', {reverse: true});
				$('#docNumber3').mask('000.000.000-00', {reverse: true});

				let s = $('#docType').html()
				$('#docType2').html(s)
				$('#docType3').html(s)
			}, 2000)
		});

		$('#docType').change(() => {
			let tp = $('#docType').val()
			if(tp == 'CNPJ'){
				$('#docNumber').mask('00.000.000/0000-00', {reverse: true});
				$('#docNumberCartao').mask('00.000.000/0000-00', {reverse: true});
				$('#docNumber3').mask('00.000.000/0000-00', {reverse: true});
			}else{
				$('#docNumber').mask('000.000.000-00', {reverse: true});
				$('#docNumberCartao').mask('000.000.000-00', {reverse: true});
				$('#docNumber3').mask('000.000.000-00', {reverse: true});
			}
		})

		function tipoPag(tipo){
			$('.collapse').removeClass('show')
			$('.btn').removeClass('active-btn')
			$('.btn-'+tipo).addClass('active-btn')
			$('#'+tipo+'Collapse').addClass('show')

		}

		$('#cardNumber').keyup(() => {
			let cardnumber = $('#cardNumber').val().replaceAll(" ", "");
			if (cardnumber.length >= 6) {
				let bin = cardnumber.substring(0,6);

				window.Mercadopago.getPaymentMethod({
					"bin": bin
				}, setPaymentMethod);
			}
		})

		function setPaymentMethod(status, response) {
			if (status == 200) {
				let paymentMethod = response[0];
				document.getElementById('paymentMethodId').value = paymentMethod.id;

				$('#band-img').attr("src", paymentMethod.thumbnail);

				$('.card-band').css('display', 'block')
				getIssuers(paymentMethod.id);
			} else {
				alert(`payment method info error: ${response}`);
			}
		}

		function getIssuers(paymentMethodId) {
			window.Mercadopago.getIssuers(
				paymentMethodId,
				setIssuers
				);
		}

		function setIssuers(status, response) {
			if (status == 200) {
				let issuerSelect = document.getElementById('issuer');
				$('#issuer').html('');
				response.forEach( issuer => {
					let opt = document.createElement('option');
					opt.text = issuer.name;
					opt.value = issuer.id;
					issuerSelect.appendChild(opt);
				});

				getInstallments(
					document.getElementById('paymentMethodId').value,
					document.getElementById('transactionAmount').value,
					issuerSelect.value
					);
			} else {
				alert(`issuers method info error: ${response}`);
			}
		}

		function getInstallments(paymentMethodId, transactionAmount, issuerId){
			window.Mercadopago.getInstallments({
				"payment_method_id": paymentMethodId,
				"amount": parseFloat(transactionAmount),
				"issuer_id": parseInt(issuerId)
			}, setInstallments);
		}

		function setInstallments(status, response){
			if (status == 200) {
				document.getElementById('installments').options.length = 0;
				response[0].payer_costs.forEach( payerCost => {
					let opt = document.createElement('option');
					opt.text = payerCost.recommended_message;
					opt.value = payerCost.installments;
					document.getElementById('installments').appendChild(opt);
				});
			} else {
				alert(`installments method info error: ${response}`);
			}
		}

		doSubmit = false;
		document.getElementById('form_cartao').addEventListener('submit', getCardToken);
		function getCardToken(event){

			let docNumberCartao = $('#docNumberCartao').val()
			docNumberCartao = docNumberCartao.replace(/[^0-9]/g, '')
			$('#docNumberCartao').val(docNumberCartao)
			event.preventDefault();
			if(!doSubmit){
				let $form = document.getElementById('form_cartao');
				window.Mercadopago.createToken($form, setCardTokenAndPay);
				return false;
			}
		};

		function setCardTokenAndPay(status, response) {
			if (status == 200 || status == 201) {
				let form = document.getElementById('form_cartao');
				let card = document.createElement('input');
				card.setAttribute('name', 'token');
				card.setAttribute('type', 'hidden');
				card.setAttribute('value', response.id);
				console.log(card)
				form.appendChild(card);
				doSubmit=true;

				form.submit();
			} else {
				alert("Verify filled data!\n"+JSON.stringify(response, null, 4));
			}
		};
	</script>
	@endsection

