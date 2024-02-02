<div class="row">
    <div class="col-md-2">
        {!!Form::tel('total_presencas', 'Total de presenças')
        ->attrs(['class' => 'qtd'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('grau', 'Grau')
        ->attrs(['class' => 'qtd'])
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::select('faixa_id', 'Faixa', $faixas->pluck('nome', 'id'))
        !!}
    </div>

     <div class="col-md-12">
        {!!Form::text('descricao', 'Descrição')
        !!}
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>