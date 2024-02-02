<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/checkout/{transacao_id}', 'PagamentoController@status');
Route::get('/checkout_loja/{transacao_id}', 'LojaController@status');
Route::get('/checkout_contrib/{transacao_id}', 'ContribuicaoController@status');
Route::post('/aluno/salvarToken', 'AlunoController@salvarToken');

Route::get('/graficoDias', 'DashboardController@graficoDias');
Route::get('/graficoFaixas/{id}', 'DashboardController@graficoFaixas');
Route::get('/dataAniversario/{id}', 'DashboardController@dataAniversario');
Route::get('/buscarTreinos', 'PresencaController@buscarTreinos');
