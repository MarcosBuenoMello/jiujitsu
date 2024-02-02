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
        {!!Form::select('faixa_id', 'Faixa', [null => 'Selecione...'] + $faixas->pluck('nome',
        'id')->all())
        !!}
    </div>

    @if(session('user_logged')['master'])
    <div class="col-md-2">
        {!!Form::select('status', 'Status', ['1' => 'Ativo', '0' => 'Inátivo'])
        !!}
    </div>
    @endif

    <div class="col-md-12">
        {!!Form::textarea('descricao', 'Descrição')
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
        <img style="" src="/posicoes/{{$item->imagem}}" id="preview" class="img-thumbnail">
        @else
        <img style="display: none;" src="" id="preview" class="img-thumbnail">
        @endif
    </div>

    @if(session('user_logged')['master'])
    @if(!isset($item))
    <div class="col-md-4 tp-link-hidden">
        <input type="file" name="video_url" class="file_video" accept="video/*">
        <div class="input-group my-3">
            <input type="text" class="form-control mt-3" disabled placeholder="Video" id="file_video">
            <div class="input-group-append">
                <button type="button" class="browse-video btn btn-primary mt-3">Procurar...</button>
            </div>
        </div>

        <video style="display: none;" class="video-view" width="400" controls><source src="" id="preview-video">
        </video>
        <label style="display: none" class="size-video"></label>
    </div>
    @endif
    @endif

</div>
@if(!isset($item))
<div class="row">
    <div class="col-md-2 mt-3">
        <br>
        {!!Form::checkbox('upload_link', 'Upload por link')
        !!}
    </div>
    <div class="col-md-5 tp-link" style="display: none">
        {!!Form::text('link', 'Link')
        !!}
    </div>
    <div class="col-md-3 tp-link" style="display: none">
        {!!Form::select('tipo', 'Tipo', ['google_drive' => 'Google drive', 'youtube' => 'Youtube'])
        !!}
    </div>
</div>
@endif
<div class="row">
    <div class="col-12">

        <button id="btn-salvar" type="button" class="btn btn-success float-right mt-4">
            Salvar
            <i style="display: none" class="spinner-border spinner-border-sm mb-1"></i>
        </button>

    </div>
</div>
