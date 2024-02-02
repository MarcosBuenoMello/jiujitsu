<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoAcesso extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id', 'aluno_id'
    ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
