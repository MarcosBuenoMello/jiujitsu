@extends('default', ['title' => 'Editar produto'])
@section('content')

<div class="row">
	<div class="card">
		<div class="card-body">
			<div class="row align-items-center">
				<div class="col-md-8">
					<h3 class="mb-0">Editar produto</h3>
				</div>
				<div class="col-md-4 text-right">
					<a href=" {{route('produtos.index')}} " class="btn btn-sm btn-primary">Voltar</a>
				</div>
			</div>
		</div>
		<div class="card-body">

			{!!Form::open()->fill($item)
			->put()
			->id('form-produto')
			->autocomplete('off')
			->route('produtos.update', [$item->id])
			->multipart()!!}
			<div class="pl-lg-4">
				@include('produtos._forms')
			</div>
			{!!Form::close()!!}

		</div>
	</div>
</div>
@endsection
