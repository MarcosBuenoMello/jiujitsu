@extends('default', ['title' => 'Loja'])
@section('content')
<style type="text/css">
	.img-prod{
		height: 350px;
		width: 100%;
	}

</style>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				{!!Form::open()->fill(request()->all())
				->get()
				!!}
				<div class="row">
					<div class="col-md-3">
						{!!Form::text('search', 'Pesquisar por nome do produto')
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('categoria_id', 'Categoria', [null => 'Selecione...'] + $categorias->pluck('nome',
						'id')->all())
						!!}
					</div>

					<div class="col-md-2">
						{!!Form::select('ordem', 'Ordar por', ['' => 'Selecione...', 'menor_valor' => 'Menor valor', 'maior_valor' => 'Maior valor', 'order_alfabetica' => 'Ordem alfabética'])
						!!}
					</div>

					<div class="col-md-2 text-left mt-1">
						<br>
						<button class="btn btn-sm  btn-primary" style="font-size: 9px;" type="submit"><svg
							xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor"
							class="bi bi-funnel-fill" viewBox="0 0 16 16">
							<path
							d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
						</svg> Filtrar</button>
						<a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger"
						href="{{ route('loja.produtos') }}"><svg xmlns="http://www.w3.org/2000/svg" width="9"
						height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
						<path
						d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" /></svg> Limpar</a>
					</div>
				</div>
				{!!Form::close()!!}
				<div class="row">
					@foreach($data as $item)
					<div class="col-md-4 col-12">
						<div class="card">
							<div class="card-header">
								{{$item->nome}}	
							</div>

							<div class="card-body">
								<img loading="lazy" class="img-prod" src="{{$item->image_main}}">
								<h5 class="mt-2">Valor: <strong>R$ {{ number_format($item->valor, 2, ',', '.') }}</strong></h5>
								@if($item->tamanho != '')
								<h5>Tamanho: <strong>{{$item->tamanho}}</strong></h5>
								@else
								<h5><br></h5>
								@endif

								@if($item->descricao != '')

								<div class="text-truncate">
									{{$item->descricao}}
								</div>
								@else
								<div class="text-truncate">
									<br>
								</div>
								@endif
							</div>

							<div class="card-footer">
								<a class="btn btn-success w-100" href="/loja/produto_detalhe/{{$item->id}}">
									<i class="la la-shopping-cart"></i>
									Comprar
								</a>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<div class="col-12">
				<nav class="d-flex justify-content-end" aria-label="...">
					{{ $data->appends(request()->all())->links() }}
				</nav>
			</div>
		</div>
	</div>
</div>
@endsection

