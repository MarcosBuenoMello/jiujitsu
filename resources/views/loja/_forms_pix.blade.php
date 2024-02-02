{!!Form::open()
->post()
->id('form_pix')
->route('loja.pagamento-pix')
!!}
<div class="row">
    <div class="col-md-2">
        {!!Form::text('nome', 'Nome')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->nome)
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('sobre_nome', 'Sobre Nome')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->sobre_nome)
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::text('email', 'Email')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->email)
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('doc_type', 'Tipo de documento')
        ->attrs(['class' => 'form-control', 'data-checkout' => 'docType'])
        ->id('docType')
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::tel('docNumber', 'Número documento')
        ->attrs(['class' => 'form-control'])
        ->value('')
        ->id('docNumber')
        !!}
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Gerar Qr Code</button>
    </div>
</div>
{!!Form::close()!!}
