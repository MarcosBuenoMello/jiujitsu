<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recompensa;
use App\Models\Faixa;
class RecompensaSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intPresenca = 30;

        $faixas = Faixa::all();
        foreach($faixas as $key => $faixa){
            for($i=0; $i<= 4; $i++){
                if($key > 0 || $i > 0){
                    Recompensa::create([
                        'faixa_id' => $faixa->id,
                        'grau' => $i,
                        'total_presencas' => $intPresenca,
                        'descricao' => 'Está apto, porém sob análise do mestre'
                    ]);

                    $intPresenca += 15;
                }
            }
            $intPresenca += 30;
        }
    }
}
