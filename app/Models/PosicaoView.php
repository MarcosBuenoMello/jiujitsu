<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosicaoView extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'posicao_id' ];

    public function posicao(){
        return $this->belongsTo(Posicao::class, 'posicao_id');
    }

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}
