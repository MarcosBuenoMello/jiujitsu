<div class="row">
    <div class="col-md-2">
        {!!Form::select('faixa_id', 'Faixa', [null => 'Selecione...'] + $faixas->pluck('nome', 'id')->all())
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('aluno_id', 'Aluno', [null => 'Selecione...'] + $alunos->pluck('full_name', 'id')->all())
        ->attrs(['class' => 'select2'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('grau', 'Grau', App\Models\Faixa::graus())
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::date('data', 'Data')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>