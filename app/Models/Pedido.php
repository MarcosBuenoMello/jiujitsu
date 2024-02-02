<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id', 'total', 'observacao', 'qr_code', 'qr_code_base64', 'transacao_id', 'status',
        'tipo_pagamento', 'carrinho', 'link_boleto'
    ];

    public function itens(){
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function somaItens(){
        $soma = 0;
        foreach($this->itens as $i){
            $soma += $i->quantidade * $i->valor;
        }
        return $soma;
    }
}
