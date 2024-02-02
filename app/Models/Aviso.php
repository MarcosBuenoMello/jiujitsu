<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [ 'titulo', 'texto', 'imagem' ]; 

    public function views(){
        return $this->hasMany(AvisoView::class, 'aviso_id');
    }
}
