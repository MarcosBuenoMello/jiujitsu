<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExameFaixa extends Model
{
    use HasFactory;

    protected $fillable = [ 'faixa_id', 'descricao' ];

    public function faixa(){
        return $this->belongsTo(Faixa::class, 'faixa_id');
    }

    public function posicoes(){
        return $this->hasMany(ExameFaixaPosicao::class, 'exame_id');
    }
}
