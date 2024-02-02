@extends('default', ['title' => 'Push'])
@section('content')

<div class="row">
	<div class="col-xl-12 order-xl-1">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h5 class="mb-0">Notificação Push aluno <strong>{{$item->nome}} {{$item->sobre_nome}}</strong></h5>
					</div>
					<div class="col-md-4 text-right">
						<a href="/aluno" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()
				->put()
				->route('aluno.push-put', [$item->id])
				->multipart()!!}
				<div class="pl-lg-4">
					<div class="row">
						<div class="col-md-3">
							{!!Form::text('titulo', 'Titulo')
							->required()
							->attrs(['class' => 'form-control'])
							!!}
						</div>
						<div class="col-md-12">
							{!!Form::text('mensagem', 'Mensagem')
							->required()
							->attrs(['class' => 'form-control'])
							!!}
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-success float-right mt-4">Enviar</button>
						</div>
					</div>
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>


@endsection