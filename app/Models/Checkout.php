<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'sobre_nome', 'email', 'documento', 'aluno_id', 'transacao_id',
        'status', 'valor', 'forma_pagamento', 'qr_code', 'qr_code_base64'
    ];
}
