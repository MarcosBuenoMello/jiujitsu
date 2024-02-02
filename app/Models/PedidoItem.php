<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id', 'produto_id', 'quantidade', 'valor'
    ];

    public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
