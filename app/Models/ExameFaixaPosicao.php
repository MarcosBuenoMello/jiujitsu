<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExameFaixaPosicao extends Model
{
    use HasFactory;

    protected $fillable = [ 'posicao_id', 'exame_id' ];

    public function posicao(){
        return $this->belongsTo(Posicao::class, 'posicao_id');
    }
}
