<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'sobre_nome', 'email', 'celular', 'sexo', 'status', 'senha', 'imagem', 
        'peso_atual', 'cidade_id', 'permitir_cadastrar_posicao', 'valor_mensalidade',
        'token', 'tamanho_faixa', 'tamanho_kimino', 'data_nascimento', 'modalidade', 'escola_id',
        'cidade_id2'
    ];

    public function getFullNameAttribute()
    {
        return $this->nome . ' ' . $this->sobre_nome;
    }

    public function modIsJiu(){
        $mod = explode(";", $this->modalidade);
        return in_array("mod_jiu", $mod);
    }

    public function modIsDef(){
        $mod = explode(";", $this->modalidade);
        return in_array("mod_def", $mod);
    }

    public function cidade(){
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }

    public function cidade2(){
        return $this->belongsTo(Cidade::class, 'cidade_id2');
    }

    public function escola(){
        return $this->belongsTo(Escola::class, 'escola_id');
    }

    public function ultimaGraduacao(){
        return $this->hasOne(AlunoGraduacao::class, 'aluno_id', 'id')->orderBy('data', 'desc');
    }

    public function graduacoes(){
        return $this->hasMany(AlunoGraduacao::class, 'aluno_id', 'id')->orderBy('data', 'desc');
    }

    public function anotacoes(){
        return $this->hasMany(AlunoAnotacao::class, 'aluno_id', 'id')->orderBy('id', 'desc');
    }

    public function checkouts(){
        return $this->hasMany(Checkout::class, 'aluno_id', 'id')->orderBy('id', 'desc');
    }

    public function ultimosAcessos(){
        return $this->hasMany(AlunoAcesso::class, 'aluno_id', 'id')->orderBy('id', 'desc')->limit(5);
    }

    public function todosAcessos(){
        return $this->hasMany(AlunoAcesso::class, 'aluno_id', 'id')->orderBy('id', 'desc');
    }

    public function ultimosAcessosPosicoes(){
        return $this->hasMany(PosicaoView::class, 'aluno_id', 'id')->orderBy('id', 'desc')->limit(5);
    }

    public function todosAcessosPosicoes(){
        return $this->hasMany(PosicaoView::class, 'aluno_id', 'id')->orderBy('id', 'desc');
    }

    public function treinos(){
        return $this->hasMany(AlunoTreino::class, 'aluno_id', 'id')->where('status', 1);
    }

    public function treinosAll(){
        return $this->hasMany(AlunoTreino::class, 'aluno_id', 'id');
    }

    public function treinosPosGraucao($date){
        $data = AlunoTreino::where('aluno_id', $this->id)
        ->where('status', 1)
        ->selectRaw('count(*) as cont')
        ->whereDate('created_at', '>=', $date)
        ->first();
        
        return $data->cont;
    }

    public function ultimosPagamentos(){
        return $this->hasMany(Mensalidade::class, 'aluno_id', 'id')->orderBy('id', 'desc')
        ->limit(5);
    }

    public function ultimasContribuicoes(){
        return $this->hasMany(Contribuicao::class, 'aluno_id', 'id')->orderBy('id', 'desc')
        ->where('status', 'approved')
        ->limit(5);
    }

    public function todasContribuicoes(){
        return $this->hasMany(Contribuicao::class, 'aluno_id', 'id')->orderBy('id', 'desc')
        ->where('status', 'approved');
    }

    public function contribAll(){
        return $this->hasMany(Contribuicao::class, 'aluno_id', 'id');
    }

    public function ultimosPagamento(){
        return $this->hasOne(Mensalidade::class, 'aluno_id', 'id')->orderBy('id', 'desc')
        ->first();
    }

    public function todosPagamento(){
        return $this->hasMany(Mensalidade::class, 'aluno_id', 'id')->orderBy('id', 'desc');
    }

    public function views(){
        return $this->hasMany(AvisoView::class, 'aluno_id', 'id');
    }

    public function comentarioVideos(){
        return $this->hasMany(ComentarioVideo::class, 'aluno_id', 'id');
    }

    public function posicoes(){
        return $this->hasMany(Posicao::class, 'aluno_id', 'id');
    }

    public function boletins(){
        return $this->hasMany(AlunoBoletin::class, 'aluno_id', 'id');
    }

    public function faixa_border(){
        $ult = $this->ultimaGraduacao;
        if($ult){
            if($ult->faixa->nome == 'Preta'){
                return 'border-left-dark';
            }
            if($ult->faixa->nome == 'Preta'){
                return 'border-left-dark';
            }else if($ult->faixa->nome == 'Marron'){
                return 'border-left-brow';
            }else if($ult->faixa->nome == 'Roxa'){
                return 'border-left-purple';
            }else if($ult->faixa->nome == 'Azul'){
                return 'border-left-primary';
            }else{
                return 'border-left-white';
            }
        }else{
            return 'border-left-white';
        }
    }

    public static function tamanhos(){
        return [
            'M0',
            'M1',
            'M2',
            'M3',
            'M4',
            'A0',
            'A1',
            'A2',
            'A3',
            'A4',
            'A5',
        ];
    }

}
