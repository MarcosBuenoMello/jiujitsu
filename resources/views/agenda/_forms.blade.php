<div class="row">

    <div class="col-md-2">
        {!!Form::select('modalidade_id', 'Modalidade', $modalidades->pluck('nome', 'id'))
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('cidade_id', 'Cidade', $cidades->pluck('nome', 'id'))
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('horario', 'Horário')
        ->attrs(['class' => 'horario'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('dia_semana', 'Dia da semana', App\Models\Agenda::diasDaSemana())
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('sexo', 'Sexo',
        ['t' => 'Todos', 'f' => 'Feminino', 'm' => 'Masculino'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('status', 'Status', ['1' => 'Ativo', '0' => 'Inátivo'])
        !!}
    </div>

</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>