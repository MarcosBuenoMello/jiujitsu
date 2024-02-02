<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'modalidade_id', 'horario', 'dia_semana', 'sexo', 'cidade_id', 'status'
    ];

    public static function diasDaSemana(){
        return [
            'domingo' => 'Domingo',
            'segunda' => 'Segunda',
            'terça' => 'Terça',
            'quarta' => 'Quarta',
            'quinta' => 'Quinta',
            'sexta' => 'Sexta',
            'sábado' => 'Sábado',
        ];
    }

    public function treino(){
        return Treino::where('data', $this->date())
        ->where('agenda_id', $this->id)
        ->first();
    }

    public function modalidade(){
        return $this->belongsTo(Modalidade::class, 'modalidade_id');
    }

    public function cidade(){
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }

    public function date(){
        
        return $this->calculaDataSemana($this->dia_semana);
    }

    private function calculaDataSemana($dia_semana){

        $diasSemana = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];
        $diaSemanaTreino = array_search($dia_semana, $diasSemana);
        $diaSemanaHoje = date('w');

        $aux = $diaSemanaTreino - $diaSemanaHoje;

        $dataHoje = date('Y-m-d');

        $dataTreino = date('Y-m-d', strtotime($dataHoje. " $aux days"));
        return $dataTreino;

    }
}
