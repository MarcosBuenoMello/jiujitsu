<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoAcesso extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'ip' ];
}
