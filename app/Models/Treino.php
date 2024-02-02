<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treino extends Model
{
    use HasFactory;

    protected $fillable = ['agenda_id', 'descricao', 'data', 'status'];

    public function alunoConfirmado(){
        $user = session('user_logged');
        
        return AlunoTreino::
        where('aluno_id', $user['aluno']->id)
        ->where('treino_id', $this->id)
        ->first();
    }

    public function agenda(){
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }

    public function alunos(){
        return $this->hasMany(AlunoTreino::class, 'treino_id');
    }
}
