<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoGaleria extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id', 'imagem'
    ];

    public function getImageAttribute()
    {
        $image_url = asset('/produtos_image/' . rawurlencode($this->imagem));
        
        return $image_url;
    }
}
