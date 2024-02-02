{!!Form::open()
->post()
->id('form_cartao')
->route('loja.pagamento-cartao')
!!}
<div class="row">
    
    <div class="col-md-4">
        {!!Form::text('cardholderName', 'Titular do cartão')
        ->required()
        ->attrs(['data-checkout' => 'cardholderName'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('doc_type', 'Tipo de documento')
        ->attrs(['class' => 'form-control', 'data-checkout' => 'docType'])
        ->id('docType2')
        !!}
    </div>    

    <div class="col-md-3">
        {!!Form::tel('docNumber', 'Número documento')
        ->attrs(['class' => 'form-control', 'data-checkout' => 'docNumber'])
        ->value('')
        ->id('docNumberCartao')
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::text('email', 'Email')
        ->attrs(['class' => 'form-control'])
        ->value($aluno->email)
        ->attrs(['data-checkout' => 'payerEmail'])
        ->required()
        !!}
    </div>

    <div class="col-md-4">
        <div class="row">
            <div class="col-10">

                {!!Form::text('cardNumber', 'Número do cartão')
                ->required()
                ->id('cardNumber')
                ->attrs(['data-checkout' => 'cardNumber', 'class' => 'cardNumber'])
                !!}

            </div>
            <div class="col-2 card-band">
                <img id="band-img" style="width: 20px; margin-top: 30px;" src="">
            </div>
        </div>
    </div>

    <div class="col-md-3">
        {!!Form::select('installments', 'Parcelas', [])
        ->required()
        ->id('installments')
        !!}
    </div>

    <div class="col-md-2">

        {!!Form::tel('cardExpirationMonth', 'Venc. Mês')
        ->attrs(['data-checkout' => 'cardExpirationMonth', 'class' => 'cardExpirationMonth'])
        ->required()
        !!}
    </div> 

    <div class="col-md-2">

        {!!Form::tel('cardExpirationYear', 'Venc. Ano')
        ->attrs(['data-checkout' => 'cardExpirationYear', 'class' => 'cardExpirationMonth'])
        ->required()
        !!}

    </div>

    <div class="col-md-2">
        {!!Form::tel('securityCode', 'Código de segurança')
        ->attrs(['data-checkout' => 'securityCode', 'class' => 'securityCode'])
        ->required()
        !!}
    </div>

    <input style="visibility: hidden" name="paymentMethodId" id="paymentMethodId" />
    <input style="visibility: hidden;" type="" name="transactionAmount" id="transactionAmount" value="{{$carrinho->total}}" />

    <select style="visibility: hidden"  class="custom-select" id="issuer" name="issuer" data-checkout="issuer">
    </select>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Pagar com cartão</button>
    </div>
</div>
{!!Form::close()!!}
