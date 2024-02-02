{!!Form::open()
->post()
->id('form_boleto')
->route('loja.pagamento-boleto')
!!}
<div class="row">
    <div class="col-md-2">
        {!!Form::text('nome', 'Nome')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->nome)
        ->required()
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('sobre_nome', 'Sobre Nome')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->sobre_nome)
        ->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::text('email', 'Email')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->email)
        ->required()
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('doc_type', 'Tipo de documento')
        ->attrs(['class' => 'form-control', 'data-checkout' => 'docType'])
        ->id('docType3')
        ->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::tel('docNumber', 'Número documento')
        ->attrs(['class' => 'form-control'])
        ->value('')
        ->id('docNumber3')
        ->required()
        !!}
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Gerar Boleto</button>
    </div>
</div>
{!!Form::close()!!}
