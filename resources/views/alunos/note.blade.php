@extends('default', ['title' => 'Anotações'])
@section('content')

<div class="row">
	<div class="col-xl-12 order-xl-1">
		<div class="card">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-8">
						<h5 class="mb-0">Anotações do aluno <strong>{{$item->nome}} {{$item->sobre_nome}}</strong></h5>
					</div>
					<div class="col-md-4 text-right">
						<a href="/aluno" class="btn btn-sm btn-primary">Voltar</a>
					</div>
				</div>
			</div>
			<div class="card-body">


				{!!Form::open()
				->put()
				->route('aluno.storeNote', [$item->id])
				->multipart()!!}
				<div class="pl-lg-4">
					<div class="row">
						<div class="col-md-12">
							{!!Form::textarea('anotacao', 'Anotação')
							->attrs(['class' => 'form-control'])
							!!}
						</div>

						<div class="col-md-2">
							{!!Form::select('status', 'Tipo', ['' => 'Selecione...', 'negativa' => 'Negativa', 'neutra' => 'Neutra', 'positiva' => 'Positiva'])
							->attrs(['class' => 'form-control'])
							!!}
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
						</div>
					</div>
				</div>
				{!!Form::close()!!}

			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-12 order-xl-1">
		<div class="card">
			<div class="card-body">
				<div class="row">
					@forelse($item->anotacoes as $anotacao)
					<div class="col-xl-4 order-xl-1">
						<div class="card">
							<div class="card-body">
								<button class="btn {{$anotacao->getColor()}} btn-block" onclick="swal('', '{{$anotacao->anotacao}}', 'info')">
									{{\Carbon\carbon::parse($anotacao->created_at)->format('d/m/Y H:i:s')}}
								</button>
							</div>
						</div>
					</div>
					@empty
					<p class="ml-3">Nenhum registro encontrado!</p>
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
@endsection