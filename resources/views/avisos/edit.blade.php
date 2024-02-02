@extends('default', ['title' => 'Editar aviso'])
@section('content')

<div class="row">
	<div class="col-12">

		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Editar aviso</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/aviso" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()->fill($item)
				->put()
				->autocomplete('off')
				->route('aviso.update', [$item->id])
				->multipart()!!}
				<div class="pl-lg-4">
					@include('avisos._forms')
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>
@endsection