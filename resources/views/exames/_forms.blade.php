<div class="row">
    <div class="col-md-8">
        {!!Form::text('descricao', 'Descrição')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <div class="col-md-2">
        {!!Form::select('faixa_id', 'Faixa', [null => 'Selecione...'] + $faixas->pluck('nome', 'id')->all())
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

</div>

<div class="row">

    <div class="col-md-4">
        {!!Form::select('posicao_id', 'Posição', [null => 'Selecione...'] + $posicoes->pluck('nome', 'id')->all())
        ->attrs(['class' => 'select2'])
        !!}
    </div>

    <div class="col-md-2">
        <br>
        <button type="button" id="add-posicao" class="btn btn-info mt-1 mb-2">
            Adicionar
        </button>
    </div>
</div>


<div class="row">
    <div class="col-md-12">


        <table class="table striped">
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>

    <input type="hidden" value="{{ isset($item) ? $item->posicoes : old('posicoes')}}" name="posicoes" id="posicoes">
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>

@section('js')
<script type="text/javascript">
    var POSICOES = [];

    $().ready(function(){ 
        POSICOES = JSON.parse($('#posicoes').val())
        montaTabelaPosicoes()
    })
    $('#add-posicao').click(() => {
        let posicao_id = $('#inp-posicao_id').val()
        let posicao_nome = $('.select2-selection__rendered').html()
        if(posicao_id){
            isAdd(posicao_id, (is) => {
                if(!is){
                    POSICOES.push({posicao_id: posicao_id, posicao_nome: posicao_nome})
                    montaTabelaPosicoes()
                    $('#inp-posicao_id').val(null).change()
                }else{
                    swal("Atenção", "Posição já esta adicionada", "error")
                }
            })
            
        }else{
            swal("Atenção", "Selecione a posição", "error")
        }
    })

    function montaTabelaPosicoes(){
        let html = ''
        POSICOES.map((p) => {
            html += '<tr>'
            html += '<td>'+p.posicao_nome+'</td>'
            html += '<td><button type="button" class="btn btn-sm btn-danger" onclick="deletePosicao('+p.posicao_id+')"><i class="la la-trash"></i></button></td>'
            html += '</tr>'
        })
        $('#posicoes').val(JSON.stringify(POSICOES))

        $('.table tbody').html(html)
    }

    function isAdd(posicao_id, call){
        let p = POSICOES.filter((x) => {
            return x.posicao_id == posicao_id
        })

        call(p.length > 0)
    }

    function deletePosicao(id){
        let pTemp = POSICOES.filter((x) => {
            return x.posicao_id != id
        })

        POSICOES = pTemp
        montaTabelaPosicoes()
    }
</script>
@endsection