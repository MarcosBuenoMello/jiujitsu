<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribuicao extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor', 'aluno_id', 'transacao_id', 'status', 'qr_code_base64', 'qr_code', 
        'forma_pagamento', 'email', 'cpf'
    ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}
