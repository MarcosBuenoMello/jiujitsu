<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cidade;
class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cidade::create([
            'nome' => 'Jaguariaíva'
        ]);
        Cidade::create([
            'nome' => 'Arapoti'
        ]);
        
    }
}
