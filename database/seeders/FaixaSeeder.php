<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faixa;

class FaixaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faixa::create([
            'nome' => 'Branca'
        ]);

        Faixa::create([
            'nome' => 'Cinza e branca'
        ]);
        Faixa::create([
            'nome' => 'Cinza'
        ]);
        Faixa::create([
            'nome' => 'Cinza e preta'
        ]);

        Faixa::create([
            'nome' => 'Amarela e branca'
        ]);
        Faixa::create([
            'nome' => 'Amarela'
        ]);
        Faixa::create([
            'nome' => 'Amarela e preta'
        ]);

        Faixa::create([
            'nome' => 'Laranja e branca'
        ]);
        Faixa::create([
            'nome' => 'Laranja'
        ]);
        Faixa::create([
            'nome' => 'Laranja e preta'
        ]);

        Faixa::create([
            'nome' => 'Verde e branca'
        ]);
        Faixa::create([
            'nome' => 'Verde'
        ]);
        Faixa::create([
            'nome' => 'Verde e preta'
        ]);

        Faixa::create([
            'nome' => 'Azul'
        ]);
        Faixa::create([
            'nome' => 'Roxa'
        ]);
        Faixa::create([
            'nome' => 'Marrom'
        ]);
        Faixa::create([
            'nome' => 'Preta'
        ]);
    }
}
