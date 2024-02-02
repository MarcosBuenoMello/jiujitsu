<div class="row">
    <div class="col-md-4">
        {!!Form::text('nome', 'Nome')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('categoria_id', 'Categoria', [null => 'Selecione...'] + $categorias->pluck('nome',
        'id')->all())
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('tamanho', 'Tamanho')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('estoque', 'Estoque')
        ->value(isset($item) ? $item->estoque : 1)
        ->attrs(['class' => 'qtd'])
        !!}
    </div>

     <div class="col-md-2">
        {!!Form::text('valor', 'Valor')
        ->attrs(['class' => 'money'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('status', 'Status', ['1' => 'Ativo', '0' => 'Inátivo'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('destaque', 'Destaque', ['0' => 'Não', '1' => 'Sim'])
        !!}
    </div>

    <div class="col-md-12">
        {!!Form::textarea('descricao', 'Descrição')
        ->attrs(['rows' => 10])
        !!}
    </div>

    <div class="col-md-4">
        <input type="file" name="file" class="file" accept="image/*">
        <div class="input-group my-3">
            <input type="text" class="form-control mt-3" disabled placeholder="Foto" id="file">
            <div class="input-group-append">
                <button type="button" class="browse btn btn-primary mt-3">Procurar...</button>
            </div>

        </div>
        @if(isset($item) && $item->image_main != "")
        <img style="" src="{{$item->image_main}}" id="preview" class="img-thumbnail">
        @else
        <img style="display: none;" src="" id="preview" class="img-thumbnail">
        @endif
    </div>

</div>

<div class="row">
    <div class="col-12">

        <button id="btn-salvar" type="submit" class="btn btn-success float-right mt-4">
            Salvar
            <i style="display: none" class="spinner-border spinner-border-sm mb-1"></i>
        </button>

    </div>
</div>
