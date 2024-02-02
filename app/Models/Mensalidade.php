<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'valor', 'forma_pagamento', 'observacao', 'data_pagamento' ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public static function formasDePagamento(){
        return [
            '' => 'Selecione...',
            'dinheiro' => 'Dinheiro',
            'pix' => 'Pix',
            'cartão de crédito' => 'Cartão de crédito',
            'cartão de débito' => 'Cartão de débito',
            'transferência' => 'Transferência',
            'cheque' => 'Cheque'
        ];
    }
}
