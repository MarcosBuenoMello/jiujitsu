@extends('default', ['title' => 'Visualizando exame'])
@section('content')

<div class="col-lg-12">

	<div class="card shadow mb-4">
		
		<div class="card-body">
			<div class="row">

				<div class="row">
					<div class="col-lg-12">
						<h3 class="ml-3">Faixa: <strong>{{$item->faixa->nome}}</strong></h3>
						<h4 class="ml-3">Descrição: <strong>{{$item->descricao}}</strong></h4>
						@if(session('user_logged')['master'])
						<button data-toggle="modal" data-target="#modal-aluno" class="btn btn-primary mt-1 mb-2 ml-3">
							<i class="la la-person"></i>
							Adicionar exame ao aluno(a)
						</button>
						@endif
					</div>


				</div>
				<div class="col-lg-12">

					@foreach($item->posicoes as $key => $p)
					<div class="card shadow">
						<!-- Card Header - Accordion -->
						<a href="#collapseCardExample{{$key}}" class="d-block card-header py-3" data-toggle="collapse"
						role="button" aria-expanded="true" aria-controls="collapseCardExample">
						<h6 class="m-0 font-weight-bold text-primary">{{$p->posicao->nome}}</h6>
					</a>
					<!-- Card Content - Collapse -->
					<div class="collapse @if($key == 0) show @endif" id="collapseCardExample{{$key}}">
						<div class="card-body">
							<h6>Categoria: <strong>{{$p->posicao->categoria->nome}}</strong></h6>
							<p>Descrição: <strong>{{$p->posicao->descricao == "" ? "Nada informado" : $p->posicao->descricao}}</strong></p>

							@forelse($p->posicao->videos as $video)

							@if($video->tipo == 'google_drive')
							<video class="mt-2" style="width: 100%; height: auto;" controls><source src="https://drive.google.com/uc?export=download&id={{$video->url}}" type='video/mp4'>
							</video>
							@else

							<iframe class="mt-2" width="100%" height="400" src="https://www.youtube.com/embed/{{$video->url}}?autoplay=0&showinfo=0">
							</iframe>

							@endif
							
							@empty
							<p class="ml-3">Nenhum registro encontrado!</p>
							@endforelse
						</div>
					</div>
					@endforeach
				</div>

			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="modal-aluno" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		{!!Form::open()->fill($item)
		->put()
		->route('exame.includeAluno', [$item->id])
		->multipart()!!}
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Incluir aluno para exame</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="col-12">
				
				<div class="col-md-6 tp-link">
					{!!Form::select('aluno_id', 'Aluno', [null => 'Selecione...'] + $alunos->pluck('full_name', 'id')->all())
					->required()
					!!}
				</div>

				<div class="col-md-12">
					{!!Form::text('observacao', 'Observação')
					->attrs(['class' => 'form-control'])
					!!}
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-success" type="submit">Salvar</button>
			</div>
		</div>
		{!!Form::close()!!}

	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	
</script>

@endsection
