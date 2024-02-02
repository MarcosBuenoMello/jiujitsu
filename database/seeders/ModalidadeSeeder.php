<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modalidade;

class ModalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modalidade::create([
            'nome' => 'Jiu-Jitsu'
        ]);
        Modalidade::create([
            'nome' => 'NO-GI'
        ]);
    }
}
