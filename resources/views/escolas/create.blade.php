@extends('default', ['title' => 'Cadastrar escola'])
@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Cadastrar escola</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/escolas" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()
				->post()
				->autocomplete('off')
				->route('escolas.store')
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