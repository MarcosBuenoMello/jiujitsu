@extends('default', ['title' => 'Visualizando posição'])
@section('content')

<div class="row">
	<div class="col-12">

		<div class="card shadow mb-4">
			
			<div class="card-body">
				<div class="row">
					<div class="col-lg-5">

						<div class="text-center">
							<!-- @if($item->imagem == "")
							<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="max-width: 250px;" 
							src="/images/no_image.png">
							@else
							<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="max-width: 300px; max-height: 200px;" 
							src="/posicoes/{{$item->imagem}}">
							@endif -->
						</div>
						<h5>{{$item->nome}}</h5>
						<p>Categoria: <strong>{{$item->categoria->nome}}</strong></p>
						<p>Faixa: <strong>{{ $item->faixa->nome == 'Branca' ? "Todas" : $posicao->faixa->nome }}</strong></p>
						@if($item->descricao != "")
						<p>Descrição: <strong>{{$item->descricao}}</strong></p>
						@endif
					</div>
					<div class="col-lg-7">

						<h5 class="mb-3">Video(s) da posição</h5>
						@forelse($item->videos as $video)

						@if($video->tipo == 'google_drive')
						<!-- <video class="mt-2" style="width: 100%; height: 400px;" controls><source src="\https://drive.google.com/file/d/11SDgBFGIu8rmErXrykuZs3WnRNKCZqqP/view?usp=sharing" type='video/mp4'>
						</video> -->

						<iframe src="https://drive.google.com/file/d/{{$video->url}}/preview" class="mt-2" style="width: 100%; height: 400px;" allowfullscreen>
						</iframe>

						@else

						<iframe class="mt-2" width="100%" height="400" src="https://www.youtube.com/embed/{{$video->url}}?autoplay=0&showinfo=0">
						</iframe>

						@endif
						@if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)

						<form action="{{ route('posicao.delete-video', $video->id) }}" method="post"
							id="form-{{$video->id}}">
							@csrf
							<button type="button" class="btn btn-danger btn-delete">
								<i class="la la-trash mr-2"></i>Remover video
							</button>
						</form>
						@endif
						@empty
						<p class="ml-3">Nenhum registro encontrado!</p>
						@endforelse


						@if(session('user_logged')['master'])
						{!!Form::open()->fill($item)
						->put()
						->id('form-video')
						->route('posicao.new-video', [$item->id])
						->multipart()!!}
						<input type="file" id="video_url" name="video_url" class="file_video" accept="video/*">
						<div class="input-group my-3">
							<input type="text" class="form-control mt-3" disabled placeholder="Adicionar novo video" id="file_video">
							<div class="input-group-append">
								<button type="button" class="browse-video btn btn-primary mt-3">Procurar...</button>
							</div>
						</div>

						<video style="display: none;" class="video-view" width="400" controls><source src="" id="preview-video">
						</video>
						<label style="display: none" class="size-video"></label>
						{!!Form::close()!!}
						@endif

						@if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)

						<button data-toggle="modal" data-target="#modal-video" class="btn btn-primary mt-1">
							<i class="la la-video"></i>
							Adicionar video manual
						</button>
						@endif
					</div>
				</div>

				<hr>

				{!!Form::open()
				->put()
				->id('form-coment')
				->route('posicao.new-coment', [$item->id])
				->multipart()!!}
				<div class="row">
					
					<div class="col-md-12">
						{!!Form::textarea('comentario', 'Dúvida ou comentário')
						->required()
						->attrs(['class' => 'form-control', 'minlength' => '5'])
						!!}
					</div>
					<div class="col-12" style="margin-top: -20px;">
						<button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
					</div>

				</div>
				{!!Form::close()!!}


				<hr>
				<div class="row">
					<div class="col-12">
						@foreach($item->comentarios as $c)
						<div class="card shadow mb-2">
							<div class="card-header">
								<h6 class="text-primary float-left">{{$c->aluno->full_name}}</h6>
								<h6 class="float-right">{{\Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i')}}</h6>
								
							</div>
							<div class="card-body">
								<p>
									{{$c->comentario}}
								</p>

								@if($c->resposta != '')
								<p>
									<strong><span class="text-danger ml-1">Resposta: </span>{{$c->resposta}}</strong>
								</p>
								@endif
							</div>
						</div>
						@endforeach
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-video" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		{!!Form::open()->fill($item)
		->put()
		->route('posicao.new-video-manual', [$item->id])
		->multipart()!!}
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Incluir video manual</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="row">
				<div class="col-md-8 ml-2 mr-2">
					{!!Form::text('video_id', 'ID do video no google drive')
					->attrs(['class' => 'form-control'])
					->required()
					!!}
				</div>
				<div class="col-md-3 ml-2 mr-2 tp-link">
					{!!Form::select('tipo', 'Tipo', ['google_drive' => 'Google drive', 'youtube' => 'Youtube'])
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
	$('#video_url').change(function() {
		setTimeout(() => {
			if($('.file_video').val()){
				$('#form-video').submit()
			}
		}, 15000)
	});

	$('#inp-video_id').keyup(() => {
		validaLink()
	})

	$('#inp-video_id').blur(() => {
		validaLink()
	})

	function validaLink(){
		let link = $('#inp-video_id').val()
		if(link.length > 0){
			if(link.includes('drive')){
				let sp = link.split('/')
				$('#inp-video_id').val(sp[5])
				$('#inp-tipo').val('google_drive').change()
			}else if(link.includes('youtube')){
				let sp = link.split('v=')
				$('#inp-video_id').val(sp[1])
				$('#inp-tipo').val('youtube').change()

			}else if(link.includes('youtu.be')){
				let sp = link.split('/')
				console.log(sp)
				$('#inp-video_id').val(sp[3])
				$('#inp-tipo').val('youtube').change()

			}
		}
	}
</script>

@endsection
