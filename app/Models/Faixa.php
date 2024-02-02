<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
class Faixa extends Model
{
    use HasFactory;
    protected $fillable = [ 'nome' ];
    
    public static function graus(){
        return [
            '' => 'Selecione...',
            '0' => '0 grau',
            '1' => '1 grau',
            '2' => '2 grau',
            '3' => '3 grau',
            '4' => '4 grau',
        ];
    }

    public static function allOrder(){
        $faixas = Faixa::all();
        $faixas2 = Faixa::all();
        $collection = new Collection();
        foreach($faixas as $f){
            $collection->push($f);
            if($f->nome == 'Branca'){
                foreach($faixas2 as $f2){
                    if($f2->nome != "Azul" && $f2->nome != "Roxa" && $f2->nome != "Marrom" && $f2->nome != "Preta"){
                        $collection->push($f2);
                    }
                }
            }
            $collection->push($f);
        }
        return $collection;
    }
}
