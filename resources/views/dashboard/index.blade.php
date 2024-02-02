@extends('default', ['title' => 'Dashboard'])
@section('content')
<style type="text/css">
	.img-home{
		width: 100% !important;
		height: 300px;
		margin-left: auto;
		margin-right: auto;
	}
</style>
<div class="">

	@if(sizeof($agenda) > 0)

	<h4 class="mt-2 ml-3">Treino(s) de Hoje</h4>

	<div class="row"> 
		@foreach($agenda as $a)
		@if($a->treino())
		@if($a->treino()->status)
		@if($a->sexo == 't' || $a->sexo == $sexoAluno || $master)

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card shadow h-100">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">
						Treino: {{$a->modalidade->nome}}

						@if($a->sexo != 't')
						@if($a->sexo == 'f')
						Feminino
						@else
						Masculino
						@endif
						@endif
					</h6>

				</div>
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">

							<div class="h6 mb-0 font-weight-bold text-gray-800">
								Dia da semana: <strong>{{mb_strtoupper($a->dia_semana)}}</strong>
							</div>
							<div class="h6 mb-0 font-weight-bold text-gray-800">
								Horário: <strong>{{($a->horario)}}</strong>
							</div>
							<div class="h6 mb-0 font-weight-bold text-gray-800">
								Cidade: <strong>{{$a->cidade->nome}}</strong>
							</div>

							@if($a->status == false)
							<h5 class="text-danger">Treino inátivo</h5>
							@endif
						</div>
					</div>
				</div>
				<div class="card-footer">
					@if(!$a->treino()->alunoConfirmado())
					<a class="btn btn-success btn-block" href="/treino/confirmarPresenca/{{$a->treino()->id}}">
						<i class="la la-check"></i>
						Confirmar presença
					</a>
					@if($a->treino()->descricao != "")
					<button class="btn btn-info btn-block" onclick="swal('Descrição do treino', '{{$a->treino()->descricao}}', 'info')">
						<i class="la la-comment"></i>
						Descrição
					</button>
					@endif
					@else
					<button class="btn btn-success btn-block" disabled>
						<i class="la la-check"></i>
						Você já confirmou presença no treino
					</button>
					@endif
				</div>
			</div>
		</div>

		@endif
		@endif
		@endif
		@endforeach
	</div>

	@endif

	@if(sizeof($posicoes) > 0)
	<div class="row">
		<div class="col-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-success">Últimas posições cadastradas</h6>
				</div>
				<div class="row mb-2">

					@foreach($posicoes as $posicao)
					@if($posicao->status == true)
					<div class="col-xl-4 col-md-12">
						<div class="card {{$posicao->faixa_border()}} shadow ml-2 mr-2 mt-2">

							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('posicao.view', $posicao->id) }}">{{$posicao->nome}} </a></h6>
								@if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)

								<div class="dropdown no-arrow">
									<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"aria-labelledby="dropdownMenuLink">

										<a class="dropdown-item text-warning" href="{{ route('posicao.edit', $posicao->id) }}"><i class="la la-edit mr-2"></i>Editar</a>

										<form action="{{ route('posicao.delete', $posicao->id) }}" method="post"
											id="form-{{$posicao->id}}">
											@csrf
											<button type="button" class="dropdown-item btn-delete text-danger">
												<i class="la la-trash mr-2"></i>Remover
											</button>
										</form>

										<a class="dropdown-item text-info" href="{{ route('posicao.view', $posicao->id) }}"><i class="la la-video mr-2"></i>Ver</a>

									</div>
								</div>
								@endif
							</div>
							<div class="card-body" onclick="ver('{{$posicao->id}}')">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Categoria: <strong>{{ $posicao->categoria->nome }}</strong> 
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Faixa: <strong>{{ $posicao->faixa->nome == 'Branca' ? "Todas" : $posicao->faixa->nome }}</strong>
										</div>
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Data de cadastro: <strong>{{\Carbon\Carbon::parse($posicao->created_at)->format('d/m/Y H:i')}}</strong>
										</div>

										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Videos da posição: <strong>{{ sizeof($posicao->videos) }}</strong>
										</div>

										@if(session('user_logged')['master'])
										<div class="h6 mb-0 font-weight-bold text-gray-800">
											Acessos de alunos: <strong class="text-danger">{{ sizeof($posicao->views) }}</strong>
										</div>
										@endif

									</div>
									<div class="col-12 mt-2">
										@if($posicao->imagem == "")
										<img class="rounded-circle img-home"
										src="/images/no_image.png">
										@else
										<img class="rounded-circle img-home"
										src="/posicoes/{{$posicao->imagem}}">
										@endif
									</div>


								</div>
							</div>
						</div>
					</div>
					@endif
					@endforeach
				</div>
			</div>
		</div>
	</div>

	@endif

	@if(sizeof($alunosPendentes) > 0 && session('user_logged')['master']) 
	<div class="row">
		<div class="col-md-12 col-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Aluno(s) pendentes</h6>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>Nome</th>
									<th>Cidade</th>
									<th>Celular</th>
									<th>Ação</th>
								</tr>
							</thead>

							<tbody>
								@foreach($alunosPendentes as $aluno)
								<tr>
									<td>{{ $aluno->nome }} {{ $aluno->sobre_nome }}</td>
									<td>{{ $aluno->cidade->nome }}</td>
									<td>{{ $aluno->celular }}</td>
									<td style="width: 100px;">
										<div class="row">
											<a href="/aluno/editAtivar/{{$aluno->id}}" class="btn btn-warning">
												<i class="la la-edit"></i>
											</a>

											<form action="{{ route('aluno.deleteDash', $aluno->id) }}" method="post"
												id="form-{{$aluno->id}}">
												@csrf
												<button type="button" class="btn-delete btn btn-danger ml-1">
													<i class="la la-trash"></i>
												</button>
											</form>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

	@if(session('user_logged')['master'])
	<div class="row">
		<div class="col-xl-12">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Grafico de acessos</h6>

				</div>
				<!-- Card Body -->
				<div class="card-body">
					<div class="chart-area">
						<div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
						<canvas id="myAreaChart" style="display: block; width: 511px; height: 320px;" width="511" height="320" class="chartjs-render-monitor"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	@endif

	<div class="modal fade" id="modal-nascimento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<form method="post" action="{{ route('dashboard.updateNiver')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Informe a sua data de nascimento</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="col-md-6 mt-2">
						{!!Form::tel('data', 'Data de nascimento')
						->attrs(['class' => 'form-control', 'data-mask' => '00/00/0000', 'data-mask-reverse' => 'true', 'minLength' => '10'])
						->required()
						!!}
					</div>
					<p class="text-danger ml-2">Padrão de exemplo - 05/11/1993</p>

					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
						<button class="btn btn-success" type="submit">Salvar</button>
					</div>
				</div>
			</form>
		</div>
	</div>


</div>
@endsection

@section('js')
<!-- <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>
	var firebaseConfig = {
		apiKey: "AIzaSyCO-Pp4VZcfuW4cli6byN1zAvnP66_ja44",
		authDomain: "pezzobjj.firebaseapp.com",
		projectId: "pezzobjj",
		storageBucket: "pezzobjj.appspot.com",
		messagingSenderId: "483825464881",
		appId: "1:483825464881:web:e6bbc483577af9f7b68710",
		measurementId: "G-7D114YMVZM"
	}

	firebase.initializeApp(firebaseConfig);
	const messaging = firebase.messaging();
	initFirebaseMessagingRegistration()
	function initFirebaseMessagingRegistration() {

		messaging
		.requestPermission()
		.then(function () {
			return messaging.getToken()
		})
		.then(function(token) {
			console.log(token);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				url: '/api/aluno/salvarToken',
				type: 'POST',
				data: {
					token: token,
					id: {{$id}}
				},
				dataType: 'JSON',
				success: function (response) {
					if(response == true)
					swal("Sucesso", "Notificação push habilitada!", "success")
				},
				error: function (err) {
					console.log('User Chat Token Error'+ err);
				},
			});
		}).catch(function (err) {
			console.log('User Chat Token Error'+ err);
		});
	}
	messaging.onMessage(function(payload) {
		const noteTitle = payload.notification.title;
		const noteOptions = {
			body: payload.notification.body,
			icon: payload.notification.icon,
		};
		new Notification(noteTitle, noteOptions);
	});
</script> -->

<script type="text/javascript">

	window.OneSignal = window.OneSignal || [];
	OneSignal.push(function() {
		OneSignal.init({
			appId: "<?php echo getenv('ONE_SIGNAL_APP_ID'); ?>",
			notifyButton: {
				enable: true
			}
		});
		OneSignal.getUserId().then(function(userId) {
			// console.log(userId)

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				url: '/api/aluno/salvarToken',
				type: 'POST',
				data: {
					token: userId,
					id: {{$id}}
				},
				dataType: 'JSON',
				success: function (response) {
					if(response == true){
						// swal("Sucesso", "Notificação push habilitada!", "success")
					}
				},
				error: function (err) {
					console.log('User Chat Token Error'+ err);
				},
			});    
		});
	});

	$(function(){

		$.get("/api/dataAniversario/{{session('user_logged')['aluno']->id}}")
		.done((res) => {

		})
		.fail((err) => {
			console.log(err)
			$('#modal-nascimento').modal('show')
		})

	})

</script>
<script type="text/javascript">
	function ver(id){
		location.href = '/posicao/view/'+id
	}
</script>

<script src="/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="/js/grafico.js"></script>
<!-- <script src="/js/chart-pie.js"></script> -->

@endsection

