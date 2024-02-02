@extends('default', ['title' => 'Novo pagamento'])
@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h3 class="mb-0">Novo pagamento</h3>
					</div>
					<div class="col-md-4 text-right">
						<a href="/mensalidade" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()
				->post()
				->route('mensalidade.store')
				->multipart()!!}
				<div class="pl-lg-4">
					@include('mensalidade._forms')
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>
@endsection