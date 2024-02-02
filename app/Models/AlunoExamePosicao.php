<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoExamePosicao extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_exame_id', 'posicao_id', 'status' ];

    public function posicao(){
        return $this->belongsTo(Posicao::class, 'posicao_id');
    }
}
