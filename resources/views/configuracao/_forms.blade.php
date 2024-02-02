<div class="row">
    <div class="col-md-3">
        {!!Form::text('valor_mensalidade', 'Valor da mensalidade')
        ->attrs(['class' => 'form-control money'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('valor_contribuicao', 'Valor contribuição')
        ->attrs(['class' => 'form-control money'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::tel('dias_para_bloqueio', 'Dias para bloqueio de acesso')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::tel('dia_pagamento', 'Dia de pagamento mensalidade')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::tel('minutos_para_presenca', 'Minutos para presença (min.)')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>