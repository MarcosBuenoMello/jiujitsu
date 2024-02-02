@extends('default', ['title' => 'Dashboard'])
@section('content')
<style type="text/css">
	.img-profile{
		width: 100%;
		height: 300px;
	}

	.square{
		border: 1px solid #000;
		padding: 5px;
		border-radius: 10px;
	}

	.check{
		background: #17A673;
		border: 1px solid #17A673;

	}
</style>
<div class="">

	<div class="card">
		<div class="card-header">
			<h4>Grade de frequência</h4>
		</div>
		<div class="card-body">
			<table class="table table-responsive">

				<tbody>
					@foreach($grade as $key => $g)
					<tr>
						<td></td>
						@foreach($g as $k => $dia)
						@if($key == 'Janeiro')
						<td>
							<label style="width: 14px; font-size: 11px;">{{$dia['dia']}}</label>
						</td>
						@endif
						@endforeach
					</tr>
					<tr>
						<td class="bg-primary text-white" style="font-size: 12px; ">{{$key}}</td>
						@foreach($g as $dia)
						<td style="padding: 12px;">
							<div class="square @if($dia['status']) check @endif"></div>
						</td>
						@endforeach
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="card-fotter">
			<div class="row">
				<!-- <div class="col-md-4 col-12">
					<h6 class="ml-2">Quantidade total de aulas: <strong>{{$totalDeTreinos}}</strong></h6>
				</div> -->
				<div class="col-md-4 col-12">
					<h6 class="ml-2">Quantidade de treinos do aluno: <strong>{{$totalDeTreinosDoAluno}}</strong></h6>
				</div>
				<!-- <div class="col-md-4 col-12">
					<h6 class="ml-2">% de presença: <strong>{{$percentual}}</strong></h6>
				</div> -->
			</div>
		</div>
	</div>

</div>
@endsection

@section('js')
<script type="text/javascript">
	function ver(id){
		location.href = '/posicao/view/'+id
	}
</script>
@endsection

