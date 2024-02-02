<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosicaoVideo extends Model
{
    use HasFactory;

    protected $fillable = [ 'posicao_id', 'url', 'tipo' ];
}
