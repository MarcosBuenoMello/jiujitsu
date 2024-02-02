<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoExame extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'exame_id', 'observacao', 'resultado', 'status' ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
    public function exame(){
        return $this->belongsTo(ExameFaixa::class, 'exame_id');
    }
    public function posicoes(){
        return $this->hasMany(AlunoExamePosicao::class, 'aluno_exame_id');
    }
}
