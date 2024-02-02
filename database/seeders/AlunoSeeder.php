<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aluno;
class AlunoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Aluno::create([
            'nome' => 'Walter',
            'sobre_nome' => 'Pezzo',
            'email' => 'walter@gmail.com',
            'celular' => '43999999999',
            'sexo' => 'm',
            'status' => 1,
            'senha' => '202cb962ac59075b964b07152d234b70',
            'imagem' => '',
            'peso_atual' => 100.5,
            'cidade_id' => 2,
            'valor_mensalidade' => 0,
            'token' => ''
        ]);
    }
}
