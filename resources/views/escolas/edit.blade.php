@extends('default', ['title' => 'Editar escola'])
@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Editar escola</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/escolas" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()->fill($item)
				->put()
				->autocomplete('off')
				->route('escolas.update', [$item->id])
				->multipart()!!}
				<div class="pl-lg-4">
					@include('escolas._forms')
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>
@endsection