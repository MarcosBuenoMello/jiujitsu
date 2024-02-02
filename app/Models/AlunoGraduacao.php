<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoGraduacao extends Model
{
    use HasFactory;

    protected $fillable = [ 'faixa_id', 'aluno_id', 'grau', 'data' ];

    public function faixa(){
        return $this->belongsTo(Faixa::class, 'faixa_id');
    }

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}
