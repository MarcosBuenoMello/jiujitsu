<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faixa;
use App\Models\Aluno;
use App\Models\AlunoGraduacao;

class GraduacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faixa = Faixa::where('nome', 'Preta')->first();
        $professores = Aluno::all();

        foreach($professores as $p){
            $data = [
                'faixa_id' => $faixa->id, 
                'aluno_id' => $p->id, 
                'grau' => 0,
                'data' => date('Y-m-d')
            ];
            AlunoGraduacao::create($data);
        }
        
    }
}
