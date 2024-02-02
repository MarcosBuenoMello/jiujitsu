@extends('default', ['title' => 'Atribuir presença'])
@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Atribuir presença a aluno(a)</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/presenca" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()
				->post()
				->route('presenca.store')
				->multipart()!!}
				<div class="pl-lg-4">
					<div class="row">


						<div class="col-md-3">
							{!!Form::select('aluno_id', 'Aluno', [null => 'Selecione...'] + $alunos->pluck('full_name', 'id')->all())
							->attrs(['class' => 'select2'])
							!!}
						</div>

						<div class="col-md-6">
							{!!Form::select('treino_id', 'Treino', ['' => 'Selecione o aluno(a)'])
							->attrs(['class' => 'form-control'])
							->required()
							!!}
						</div>

					</div>
					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-success float-right mt-4">Confirmar presença</button>
						</div>
					</div>
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$(function(){
		$('#inp-aluno_id').val('').change()
	})
	$('#inp-aluno_id').change(() => {
		console.clear()
		$("#inp-treino_id").html('')
		let aluno_id = $('#inp-aluno_id').val()
		if(aluno_id){
			$.get('/api/buscarTreinos', {aluno_id: aluno_id})
			.done((res) => {
				console.log(res)
				res.map((t) => {
					var o = new Option(t.data + " - " + t.cidade.nome + " - " + t.modalidade.nome, t.id);
					$("#inp-treino_id").append(o);
				})
				
			})
			.fail((err) => {
				console.log(err)
			})
		}
	})
</script>
@endsection