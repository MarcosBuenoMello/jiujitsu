@extends('default', ['title' => 'Loja'])
@section('content')
<style type="text/css">
	.img-prod{
		height: 350px;
		width: 100%;
	}

	.img:hover{
		cursor: pointer;
	}

</style>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-5">
						<h5>{{$item->nome}}</h5>
						<div class="ecommerce-gallery" data-mdb-zoom-effect="true" data-mdb-auto-height="true">
							<div class="row py-3 shadow-5">
								<div class="col-12 mb-1">
									<div class="lightbox">
										<img loading="lazy" height="400" 
										src="{{$item->image_main}}"
										alt="Gallery image 1"
										class="ecommerce-gallery-main-img image-main active w-100"
										/>
									</div>
								</div>
								@foreach($item->galeria as $g)
								<div onclick="alteraImagem('{{$g->image}}')" class="col-3 img mt-1">
									<img loading="lazy" height="150" 
									src="{{$g->image}}"
									data-mdb-img="{{$g->image}}"
									alt="Gallery image 1"
									class="active w-100"
									/>
								</div>
								@endforeach
								
							</div>
						</div>
					</div>

					<div class="col-12 col-md-7">

						{!! $item->descricao !!}
						<br><br>
						@if($item->tamanho != '')
						<h5>Tamanho: {{$item->tamanho}}</h5>
						@endif
						<h5>R$ {{ number_format($item->valor, 2, ',', '.') }}</h5>

						<form method="post" action="/loja/add_item_carrinho">
							@csrf
							<input type="hidden" name="item_id" value="{{$item->id}}">
							<button class="btn btn-success">
								<i class="la la-shopping-cart"></i>
								Adcionar ao carrinho
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	function alteraImagem(url){

		$(".image-main").attr("src", url);
	}
</script>
@endsection


