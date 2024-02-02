<div class="row">
    <div class="col-md-4">
        {!!Form::text('titulo', 'Titulo')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-12">
        {!!Form::textarea('texto', 'Texto')
        ->attrs(['class' => 'form-control'])
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
        @if(isset($item) && $item->imagem != "")
        <img style="" src="/avisos/{{$item->imagem}}" id="preview" class="img-thumbnail">
        @else
        <img style="display: none;" src="" id="preview" class="img-thumbnail">
        @endif
    </div>
   
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>