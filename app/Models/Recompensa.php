<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    use HasFactory;
    protected $fillable = [ 'faixa_id', 'grau', 'total_presencas', 'descricao' ];

    public function faixa(){
        return $this->belongsTo(Faixa::class, 'faixa_id');
    }

    public static function totalBranca(){
        $aux = Recompensa::where('faixa_id', 1)
        ->where('grau', 4)->first();
        return $aux->total_presencas;
    }

    public static function totalAzul(){
        $aux = Recompensa::where('faixa_id', 2)
        ->where('grau', 4)->first();
        return $aux->total_presencas;
    }

    public static function primeiraAzul(){
        $aux = Recompensa::where('faixa_id', 2)
        ->where('grau', 0)->first();
        return $aux->total_presencas;
    }

    public static function totalRoxa(){
        $aux = Recompensa::where('faixa_id', 3)
        ->where('grau', 4)->first();
        return $aux->total_presencas;
    }

    public static function primeiraRoxa(){
        $aux = Recompensa::where('faixa_id', 3)
        ->where('grau', 0)->first();
        return $aux->total_presencas;
    }

    public static function totalMarrom(){
        $aux = Recompensa::where('faixa_id', 4)
        ->where('grau', 4)->first();
        return $aux->total_presencas;
    }

    public static function primeiraMarrom(){
        $aux = Recompensa::where('faixa_id', 4)
        ->where('grau', 0)->first();
        return $aux->total_presencas;
    }

    public static function primeiraPreta(){
        $aux = Recompensa::where('faixa_id', 5)
        ->where('grau', 0)->first();
        return $aux->total_presencas;
    }

    public static function totalPreta(){
        $aux = Recompensa::where('faixa_id', 5)
        ->where('grau', 4)->first();
        return $aux->total_presencas;
    }

    public static function primairaPreta(){
        $aux = Recompensa::where('faixa_id', 5)
        ->where('grau', 0)->first();
        return $aux->total_presencas;
    }

    public function faixa_border(){

        if($this->faixa->nome == 'Preta'){
            return 'text-dark';
        }else if($this->faixa->nome == 'Marron'){
            return 'text-brow';
        }else if($this->faixa->nome == 'Roxa'){
            return 'text-purple';
        }else if($this->faixa->nome == 'Azul'){
            return 'text-primary';
        }else{
            return '';
        }

    }
}
