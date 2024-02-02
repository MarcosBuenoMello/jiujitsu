@extends('default', ['title' => 'Contribuição'])
@section('content')

<style type="text/css">
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
						<h3 class="mb-0">Contribuição</h3>
					</div>
				</div>


			</div>

			<input type="hidden" value="{{$data['transacao_id']}}" id="transacao_id" name="">

			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<h6>Valor: <strong class="text-primary">{{ number_format($data['valor'], 2, ',', '.') }}</strong></h6>

						<h3 style="display: none" class="text-success status">Pagamento aprovado <i class="la la-check"></i></h3>

						<div class="col-lg-4 offset-lg-4">
							<img style="width: 300px; height: 300px;" src="data:image/jpeg;base64,{{$data['qr_code_base64']}}"/>
						</div>	
					</div>
					<div class="col-lg-12">

						<div class="col-lg-11 offset-lg-1">

							<div class="input-group">
								<input type="text" class="form-control" value="{{$data['qr_code']}}" id="qrcode_input" />

								<div class="input-group-append">
									<span class="input-group-text">

										<i onclick="copy()" class="la la-copy">
										</i>

									</span>
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

<script type="text/javascript">
	var status = 0
	var intervalStatus = null
	function copy(){
		const inputTest = document.querySelector("#qrcode_input");

		inputTest.select();
		document.execCommand('copy');

		swal("", "Código pix copado!!", "success")
	}

	intervalStatus = setInterval(() => {
		if(status == 0){
			let transacao_id = $('#transacao_id').val();
			$.get('/api/checkout_contrib/'+transacao_id)
			.done((success) => {
				if(success == "approved"){
					swal("Sucesso", "Pagamento aprovado", "success")
					status = 1
					$('.status').css('display', 'block')
					clearInterval(intervalStatus)

				}
			})
			.fail((err) => {
				console.log(err)
			})
		}
	}, 2000)
</script>
@endsection

