<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioVideo extends Model
{
    use HasFactory;

    protected $fillable = [ 'posicao_id', 'aluno_id', 'comentario', 'resposta', 'resposta_view' ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function posicao(){
        return $this->belongsTo(Posicao::class, 'posicao_id');
    }
}
