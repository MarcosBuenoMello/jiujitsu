<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisoView extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'aviso_id' ];

    public function aluno(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}
