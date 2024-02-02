@extends('default', ['title' => 'Perfil'])

@section('content')

<style type="text/css">
	input[type="file"] {
		display: none;
	}

	label.label-file {
		padding: 8px 8px;
		width: 100%;
		background-color: #4268D6;
		color: #FFF;
		text-align: center;
		display: block;
		margin-top: 10px;
		cursor: pointer;
	}

	.img-profile2{
		height: 350px;
		width: 100% !important;
	}

</style>

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-4 py-3 border-left-primary">
			<div class="card-body">
				<h1 class="h3 mb-2 text-gray-800">Dados do aluno(a)</h1>

				<div class="p-5">

					<form method="post" action="/perfil/update">
						@csrf
						<div class="form-group row">
							<div class="col-sm-6 mb-3 mb-sm-0">
								<label>Nome</label>
								<input type="text" class="form-control form-control-user @if($errors->has('nome')) is-invalid @endif" name="nome" 
								placeholder="Nome" value="{{ $aluno->nome }}">
								@if($errors->has('nome'))
								<span class="text-danger ml-2">{{ $errors->first('nome') }}</span>
								@endif
							</div>
							<div class="col-sm-6">
								<label>Sobre nome</label>
								<input type="text" class="form-control form-control-user @if($errors->has('sobre_nome')) is-invalid @endif" name="sobre_nome" 
								placeholder="Sobre nome" value="{{ $aluno->sobre_nome }}">
								@if($errors->has('sobre_nome'))
								<span class="text-danger ml-2">{{ $errors->first('sobre_nome') }}</span>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-6 mb-3 mb-sm-0">
								<label>Peso atual</label>
								<input type="text" class="form-control form-control-user @if($errors->has('peso_atual')) is-invalid @endif" name="peso_atual" 
								placeholder="Sobre nome" value="{{ $aluno->peso_atual }}">
								@if($errors->has('peso_atual'))
								<span class="text-danger ml-2">{{ $errors->first('peso_atual') }}</span>
								@endif
							</div>

							<div class="col-sm-6">
								<label>Sexo</label>
								<select name="sexo" class="form-control select-control @if($errors->has('sexo')) is-invalid @endif">
									<option value="">Sexo</option>
									<option @if($aluno->sexo == 'f') selected @endif value="f">Feminino</option>
									<option @if($aluno->sexo == 'm') selected @endif value="m">Masculino</option>
								</select>
								@if($errors->has('sexo'))
								<span class="text-danger ml-2">{{ $errors->first('sexo') }}</span>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-6">
								<label>Cidade</label>
								<select name="cidade_id" class="form-control select-control @if($errors->has('cidade_id')) is-invalid @endif">
									<option value="">Cidade</option>
									@foreach($cidades as $c)
									<option @if($aluno->cidade_id == $c->id) selected @endif value="{{$c->id}}">{{$c->nome}}</option>
									@endforeach
								</select>
								@if($errors->has('cidade_id'))
								<span class="text-danger ml-2">{{ $errors->first('cidade_id') }}</span>
								@endif
							</div>

							<div class="col-sm-6 mb-3 mb-sm-0">
								<label>Data de nascimento</label>
								<input type="tel" class="form-control form-control-user @if($errors->has('data_nascimento')) is-invalid @endif" name="data_nascimento" 
								placeholder="Data de nascimento" value="{{ $aluno->data_nascimento != null ? \Carbon\Carbon::parse($aluno->data_nascimento)->format('d/m/Y') : '' }}" data-mask="00/00/0000" data-mask-reverse="true">
								@if($errors->has('data_nascimento'))							
								<span class="text-danger ml-2">{{ $errors->first('data_nascimento') }}</span>
								@endif
							</div>
						</div>

						<button type="submit" class="btn btn-success btn-user float-right">
							Salvar
						</button>
					</form>
				</div>
			</div>
		</div>

		<div class="card mb-4 py-3 border-left-primary">
			<div class="card-body">
				<h1 class="h6 mb-2 text-gray-900">Data de cadastro <strong>{{\Carbon\Carbon::parse($aluno->created_at)->format('d/m/Y H:i')}}</strong></h1>
				<h1 class="h6 mb-2 text-gray-900">Faixa 

					@if($aluno->ultimaGraduacao)
					<strong>{{$aluno->ultimaGraduacao->faixa->nome}} {{$aluno->ultimaGraduacao->grau > 0 ? $aluno->ultimaGraduacao->grau . "º" : ''}}</strong>
					@else
					<strong>--</strong>
					@endif
				</h1>
				<h1 class="h6 mb-2 text-gray-900">Data última graduação 
					@if($aluno->ultimaGraduacao)
					<strong>{{\Carbon\Carbon::parse($aluno->ultimaGraduacao->data)->format('d/m/Y')}}</strong>
					@else
					<strong>--</strong>
					@endif

					
				</h1>
				<h1 class="h6 mb-2 text-gray-900">Peso atual <strong>{{$aluno->peso_atual}} KG</strong>
					
				</h1>

			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card mb-4 py-3 border-left-primary">
			<div class="card-body">

				@if($aluno->imagem == "")
				<img class="img-profile2 rounded-circle"
				src="/img/undraw_profile.svg">
				@else
				<img class="img-profile2 rounded-circle"
				src="/alunos/{{$aluno->imagem}}">
				@endif

				<form id="form-foto" method="post" action="/perfil/salvarFoto" enctype="multipart/form-data">
					<label class="label-file" for="file">
						<i class="la la-photo"></i>
						Alterar imagem
					</label>
					@csrf
					<input accept=".png,.jpg,.jpeg" type="file" id="file" name="file">
				</form>
				<button data-toggle="modal" data-target="#modal-alterar-senha" class="btn w-100 btn-danger mt-2">
					<i class="la la-key"></i>
					Alterar senha
				</button>

				<a href="/perfil/cronogramaPresenca" class="btn w-100 btn-info mt-2">
					<i class="la la-list"></i>
					Cronograma de presença
				</a>
			</div>
		</div>
	</div>

	<div class="col-lg-12 mt-2">
		<div class="card">
			<div class="card-header">
				<h4>Últimos pagamentos de mensalidade</h4>
			</div>
			<div class="card-body">
				<table class="table">

					<thead>
						<tr>
							<th>Data de pagamento</th>
							<th>Valor</th>
							<th>Forma de pagamento</th>
						</tr>
					</thead>

					<tbody>
						@foreach($aluno->ultimosPagamentos as $p)
						<tr>
							<td>{{ \Carbon\Carbon::parse($p->data_pagamento)->format('d/m/Y')}}</td>
							<td>R$ {{number_format($p->valor, 2, ',', '.') }}</td>
							<td>{{ mb_strtoupper($p->forma_pagamento) }}</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>

		</div>
	</div>

	<hr>

	<div class="col-lg-12 mt-2">
		<div class="card">
			<div class="card-header">
				<h4>Últimas contribuições</h4>
			</div>
			<div class="card-body">
				<table class="table">

					<thead>
						<tr>
							<th>Data de pagamento</th>
							<th>Valor</th>
						</tr>
					</thead>

					<tbody>
						@foreach($aluno->ultimasContribuicoes as $p)
						<tr>
							<td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y')}}</td>
							<td>R$ {{number_format($p->valor, 2, ',', '.') }}</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>

		</div>
	</div>

	<div class="col-lg-12 mt-2">
		<input type="hidden" id="aluno_id" value="{{$aluno->id}}">

		<div class="row">
			<div class="col-xl-12">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Progresso de graduação</h6>

					</div>
					<!-- Card Body -->
					<div class="card-body">

						@if($proxGraduacao)
						<h4>Próxima graduação: 
							<strong class="{{$proxGraduacao->faixa_border()}}">
								{{$proxGraduacao->faixa->nome}}	
								@if($proxGraduacao->grau > 0)
								{{$proxGraduacao->grau}}º grau
								@endif	
							</strong>
						</h4>

						<h5>Treinos para a próxima graduação: 
							<strong>{{$treinosPendentes}}</strong>
						</h5>
						@endif
						
						<div class="chart-pie pt-4 pb-2"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand">
							<div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
							<canvas id="myPieChart" style="display: block; width: 294px; height: 245px;" class="chartjs-render-monitor" width="294" height="245"></canvas>
						</div>
						<div class="mt-4 text-center small">
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #D9D9D9;"></i> Faixa branca
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #004AAD;"></i> Faixa azul
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #602374;"></i> Faixa roxa
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #4D3131;"></i> Faixa marrom
							</span>
							<span class="mr-2">
								<i class="fas fa-circle" style="color: #000;"></i> Faixa preta
							</span>

						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="modal-alterar-senha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		{!!Form::open()->fill($aluno)
		->put()
		->id('form-senha')
		->route('aluno.alterar-senha', [$aluno->id])
		->multipart()!!}
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alteração de senha</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="row ml-2 mr-2">
				<div class="col-md-4 col-12">
					{!!Form::text('senha_anterior', 'Senha anterior')
					->type('password')
					->value('')
					->required()
					!!}
				</div>
				<div class="col-md-4 col-12">
					{!!Form::text('senha', 'Senha')
					->type('password')
					->value('')
					->required()
					!!}
				</div>
				<div class="col-md-4 col-12">
					{!!Form::text('repita_senha', 'Repita a senha')
					->type('password')
					->required()
					->value('')
					!!}
				</div>

				<p class="text-danger msg-senha ml-2"></p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-success" id="btn-salvar-senha" disabled type="button">Salvar</button>
			</div>
		</div>
		{!!Form::close()!!}

	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
	$().ready(function(){ 
		validaSenha()
	})

	$('#file').change(function() {
		$('#form-foto').submit();
	});

	$('#inp-senha, #inp-repita_senha').keyup(() => {
		validaSenha()
	})

	function validaSenha(){
		let senha = $('#inp-senha').val()
		let repita_senha = $('#inp-repita_senha').val()

		if(senha != repita_senha){
			$('.msg-senha').html('Senhas não coencidem!');
			$('#btn-salvar-senha').attr('disabled', 'disabled')
		}else if(senha == ""){
			$('#btn-salvar-senha').attr('disabled', 'disabled')
			$('.msg-senha').html('Informe a nova senha!');
		}else{
			$('.msg-senha').html('');
			$('#btn-salvar-senha').removeAttr('disabled')
		}
	}

	$('#btn-salvar-senha').click(() => {
		$('#form-senha').submit()
	})
</script>
<script src="/vendor/chart.js/Chart.min.js"></script>
<script src="/js/chart-pie.js"></script>

@endsection