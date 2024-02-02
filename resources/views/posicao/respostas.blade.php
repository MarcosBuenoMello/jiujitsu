@extends('default', ['title' => 'Visualizando posição'])
@section('content')

<div class="row">
	<div class="col-12">

		@if(sizeof($data) > 0)
		<div class="card shadow mb-4">
			
			<div class="card-body">

				<div class="row">
					<div class="col-12">
						@foreach($data as $c)
						<div class="card shadow mb-2">
							<div class="card-header">
								<h6 class="text-primary float-left">{{$c->aluno->full_name}}</h6>
								<h6 class="float-right">{{\Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i')}}</h6>
								
							</div>
							<div class="card-body">
								<p>
									{{$c->comentario}}
								</p>

								{!!Form::open()
								->put()
								->id('form-coment')
								->route('posicao.put-coment', [$c->id])
								->multipart()!!}
								<div class="row">

									<div class="col-md-12">
										{!!Form::textarea('resposta', 'Resposta')
										->required()
										->attrs(['class' => 'form-control', 'minlength' => '5'])
										!!}
									</div>
									<div class="col-12" style="margin-top: -20px;">
										<button type="submit" class="btn btn-success float-right mt-4">
											<i class="la la-check"></i> Responder
										</button>
									</div>

								</div>
								{!!Form::close()!!}


								<form action="{{ route('posicao.resposta-destroy', $c->id) }}" method="post"
									id="form-{{$c->id}}">
									@csrf
									@method('delete')
									<button type="button" class="btn btn-sm btn-danger btn-delete">
										<i class="la la-trash"></i>
										Remover
									</button>
								</form>

							</div>
						</div>
						@endforeach
					</div>
				</div>

			</div>
		</div>
		@else
		<div class="card shadow mb-4">
			<div class="card-body">

				<p>Nenhum comentário ou dúvida para responder!</p>
			</div>
		</div>
		@endif
	</div>
</div>

@endsection

@section('js')


@endsection
