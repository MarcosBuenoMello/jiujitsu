<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoAnotacao extends Model
{
    use HasFactory;

    protected $fillable = [ 'aluno_id', 'anotacao', 'status' ];

    public function getColor(){
        if($this->status == 'negativa'){
            return 'btn-danger';
        }else if($this->status == 'positiva'){
            return 'btn-success';
        }
        return 'btn-dark';
    }
}
