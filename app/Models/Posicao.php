<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posicao extends Model
{
    use HasFactory;

    protected $fillable = [ 'nome', 'descricao', 'imagem', 'faixa_id', 'categoria_id', 
    'status', 'aluno_id', 'video_temp' ];

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function videos(){
        return $this->hasMany(PosicaoVideo::class, 'posicao_id');
    }

    public function comentarios(){
        return $this->hasMany(ComentarioVideo::class, 'posicao_id')->orderBy('id', 'desc');
    }

    public function views(){
        return $this->hasMany(PosicaoView::class, 'posicao_id');
    }

    public function faixa(){
        return $this->belongsTo(Faixa::class, 'faixa_id');
    }

    public function faixa_border(){

        if($this->faixa->nome == 'Preta'){
            return 'border-left-dark';
        }else if($this->faixa->nome == 'Marron'){
            return 'border-left-brow';
        }else if($this->faixa->nome == 'Roxa'){
            return 'border-left-purple';
        }else if($this->faixa->nome == 'Azul'){
            return 'border-left-primary';
        }else{
            return 'border-left-white';
        }

    }
}
