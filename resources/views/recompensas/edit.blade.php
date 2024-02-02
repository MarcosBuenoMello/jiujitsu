@extends('default', ['title' => 'Editar recopensa'])
@section('content')

<div class="row">
	<div class="col-xl-12 order-xl-1">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Editar recopensa</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/recompensas" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()->fill($item)
				->put()
				->route('recompensa.update', [$item->id])
				->multipart()!!}
				<div class="pl-lg-4">
					@include('recompensas._forms')
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>
@endsection