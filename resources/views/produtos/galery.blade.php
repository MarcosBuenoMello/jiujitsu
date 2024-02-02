@extends('default', ['title' => 'Galeria produto'])
@section('content')

<div class="row">
	<div class="card w-100">
		<div class="card-body">
			<div class="row align-items-center">
				<div class="col-md-8">
					<h3 class="mb-0">Galeria produto</h3>
				</div>
				<div class="col-md-4 text-right">
					<a href=" {{route('produtos.index')}} " class="btn btn-sm btn-primary">Voltar</a>
				</div>
			</div>
		</div>
		<div class="card-body">

			{!!Form::open()
			->put()
			->id('form-produto')
			->autocomplete('off')
			->route('produtos.upload_image', [$item->id])
			->multipart()!!}
			<div class="pl-lg-4">

				<div class="row">
					<div class="col-md-4">
						<input type="file" name="file" class="file" accept="image/*">
						<div class="input-group my-3">
							<input type="text" class="form-control mt-3" disabled placeholder="Foto" id="file">
							<div class="input-group-append">
								<button type="button" class="browse btn btn-primary mt-3">Procurar...</button>
							</div>

						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">

						<button id="btn-salvar" type="submit" class="btn btn-success ">
							Salvar imagem
						</button>

					</div>
				</div>

			</div>
			{!!Form::close()!!}
			<br>
			<div class="row">
				@foreach($item->galeria as $g)
				<div class="col-md-4">
					<div class="card w-100">
						<div class="card-body">
							<center><img width="250" height="300" src="{{ $g->image }}"></center>
						</div>
						<div class="card-footer">
							<form action="{{ route('produtos.destroy_image', $g->id) }}" method="post" id="form-{{$g->id}}">
								@csrf
								@method('delete')
								<button type="button" class="btn-delete btn btn-danger w-100">
									<i class="la la-trash mr-2"></i>Remover
								</button>
							</form>
						</div>
					</div>
				</div>
				@endforeach
			</div>

		</div>
	</div>
</div>
@endsection
