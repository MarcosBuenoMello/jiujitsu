<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'tamanho', 'valor', 'descricao', 'status', 'destaque', 'categoria_id',
        'estoque'
    ];

    public function getImageMainAttribute()
    {
        if(sizeof($this->galeria) > 0){
            $image_url = asset('/produtos_image/' . rawurlencode($this->galeria[0]->imagem));
        } else {
            $image_url = asset('/images/no_image2.png');
        }
        return $image_url;
    }

    public function galeria(){
        return $this->hasMany(ProdutoGaleria::class, 'produto_id');
    }

    public function categoria(){
        return $this->belongsTo(CategoriaProduto::class, 'categoria_id');
    }

    public function acessos(){
        return $this->hasMany(ProdutoAcesso::class, 'produto_id');
    }

}
