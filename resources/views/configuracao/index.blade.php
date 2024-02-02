@extends('default', ['title' => 'Configuração'])
@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Configuração</h3>
					</div>
					
				</div>
			</div>
			<div class="card-body">

				{!!Form::open()->fill($item)
				->post()
				->route('config.store')
				->multipart()!!}
				<div class="pl-lg-4">
					@include('configuracao._forms')
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>
@endsection