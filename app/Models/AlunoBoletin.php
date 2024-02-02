<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoBoletin extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'escola_id', 'observacao', 'img' ];
}
