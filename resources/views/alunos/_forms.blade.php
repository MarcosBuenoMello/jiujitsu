<div class="row">
    <div class="col-md-3">
        {!!Form::text('nome', 'Nome')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('sobre_nome', 'Sobre Nome')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('email', 'Email')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('celular', 'Celular')
        ->attrs(['class' => 'form-control celular'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('sexo', 'Sexo',
        ['f' => 'Feminino', 'm' => 'Masculino'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::tel('peso_atual', 'Peso atual')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-4">
        {!!Form::select('cidade[]', 'Cidade', $cidades->pluck('nome',
        'id')->all())
        ->attrs(['class' => 'select2'])
        ->multiple()
        ->required()
        ->value(isset($item) ? $cidades_edit : [])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('faixa_id', 'Faixa', [null => 'Selecione...'] + $faixas->pluck('nome',
        'id')->all())
        ->value((isset($item) && isset($item->ultimaGraduacao)) ? $item->ultimaGraduacao->faixa_id : '')
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('grau', 'Grau', App\Models\Faixa::graus())
        ->value((isset($item) && isset($item->ultimaGraduacao)) ? $item->ultimaGraduacao->grau : '')
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('status', 'Status', ['1' => 'Ativo', '0' => 'Inátivo'])
        ->value(isset($ativar) ? 1 : (isset($item) ? $item->status : 0))
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::select('permitir_cadastrar_posicao', 'Permitir cadastrar posições', ['0' => 'Não', '1' => 'Sim'])
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::date('data_ultima_graduacao', 'Data da ult. graduação')
        ->value((isset($item) && isset($item->ultimaGraduacao)) ? $item->ultimaGraduacao->data : '')

        !!}
    </div>

    <div class="col-md-3">
        {!!Form::tel('data_nascimento', 'Data de nascimento')
        ->value((isset($item)) ? ($item->data_nascimento != null ? \Carbon\Carbon::parse($item->data_nascimento)->format('d/m/Y') : '') : '')
        ->attrs(['data-mask' => '00/00/0000', 'data-mask-reverse' => 'true'])
        !!}
    </div>

    @if(!isset($ativar))
    <div class="col-md-2">
        {!!Form::text('senha', 'Senha')
        ->attrs(['class' => 'form-control'])
        ->type('password')
        ->value('')
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::text('repita_senha', 'Repita Senha')
        ->attrs(['class' => 'form-control'])
        ->type('password')
        !!}
    </div>
    @endif

    <div class="col-md-2">
        {!!Form::tel('valor_mensalidade', 'Valor mensalidade')
        ->attrs(['class' => 'form-control money'])
        ->value(!isset($item) ? $config != null ? $config->valor_mensalidade : '' : $item->valor_mensalidade)
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
        <img style="" src="/alunos/{{$item->imagem}}" id="preview" class="img-thumbnail">
        @else
        <img style="display: none;" src="" id="preview" class="img-thumbnail">
        @endif
    </div>

    <div class="col-md-2">
        {!!Form::select('tamanho_kimino', 'Tamanho kimono',
        ['' => 'Selecione'] + App\Models\Aluno::tamanhos())
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('tamanho_faixa', 'Tamanho faixa',
        ['' => 'Selecione'] + App\Models\Aluno::tamanhos())
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('escola_id', 'Escola', [null => 'Selecione...'] + $escolas->pluck('nome',
        'id')->all())
        !!}
    </div>


    <div class="col-md-4">
        <label>Modalidade</label>
        <br>
        <input @isset($item) @if($item->modIsJiu()) checked @endif @endif type="checkbox" name="mod_jiu"> Jiu-jitsu
        <input @isset($item) @if($item->modIsDef()) checked @endif @endif class="ml-2" type="checkbox" name="mod_def"> Defesa pessoal
        @if($errors->has('modalidade'))
        <span class="text-danger ml-2">{{ $errors->first('modalidade') }}</span>
        @endif
    </div>


</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>