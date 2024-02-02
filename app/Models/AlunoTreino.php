<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoTreino extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id' , 'treino_id', 'status' ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}
