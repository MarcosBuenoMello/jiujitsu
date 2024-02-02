<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'valor_mensalidade', 'dias_para_bloqueio', 
        'dia_pagamento', 'minutos_para_presenca', 'valor_contribuicao' 
    ];

}
