<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisoGraduacaoMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id', 'faixa_id', 'grau'
    ];
}
