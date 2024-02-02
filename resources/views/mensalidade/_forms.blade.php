<div class="row">
    
    <div class="col-md-3">
        {!!Form::select('aluno_id', 'Aluno', [null => 'Selecione...'] + $alunos->pluck('full_name', 'id')->all())
        ->attrs(['class' => 'select2'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('valor', 'Valor')
        ->attrs(['class' => 'money'])
        ->value($config->valor_mensalidade)
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('forma_pagamento', 'Forma de pagamento', App\Models\Mensalidade::formasDePagamento())
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::date('data_pagamento', 'Data de pagamento')
        ->attrs(['class' => 'form-control'])
        ->value(date('Y-m-d'))
        !!}
    </div>

    <div class="col-md-12">
        {!!Form::text('observacao', 'Observação (opcional)')
        !!}
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>