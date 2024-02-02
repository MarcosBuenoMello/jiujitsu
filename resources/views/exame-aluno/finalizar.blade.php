@extends('default', ['title' => 'Visualizando exame'])
@section('content')

<div class="col-lg-12">

	<div class="card shadow mb-4">
		
		<div class="card-body">

			<div class="row">
				<div class="col-lg-12">
					<h4 class="ml-3">Aluno: <strong>{{$item->aluno->full_name}}</strong></h4>
					<h4 class="ml-3">Faixa: <strong>{{$item->exame->faixa->nome}}</strong></h4>

				</div>
			</div>

			{!!Form::open()->fill($item)
			->put()
			->route('exame-aluno.finalizar', [$item->id])
			->multipart()!!}
			<div class="row">

				<div class="col-lg-6 ml-3">

					{!!Form::text('observacao', 'Observação')
					->value($item->observacao)
					!!}
				</div>
				<div class="col-lg-3">

					{!!Form::select('resultado', 'Resultado', ['aprovado' => 'Aprovado', 'reprovado' => 'Reprovado'])
					!!}
				</div>
			</div>

			<div class="row">
				<div class="col-12">

					<button id="btn-salvar" type="submit" class="btn btn-success float-right mt-4">
						Salvar
						<i style="display: none" class="spinner-border spinner-border-sm mb-1"></i>
					</button>

				</div>
			</div>
			{!!Form::close()!!}

		</div>
	</div>
</div>


@endsection

@section('js')
<script type="text/javascript">
	
</script>

@endsection
