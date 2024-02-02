@extends('default', ['title' => 'Cadastrar aviso'])
@section('content')

<div class="row">
	<div class="col-12">
		<div class="card w-100">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Cadastrar aviso</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/aviso" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()
				->post()
				->autocomplete('off')
				->route('aviso.store')
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