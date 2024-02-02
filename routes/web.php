<?php

use Illuminate\Support\Facades\Route;
use App\Models\Faixa;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function(){ 
	return redirect('/login');
});

// Route::get('/login', 'LoginController@index')->middleware('validaLogin');
Route::get('/login', 'LoginController@index');
Route::get('/logoff', 'LoginController@logoff');
Route::get('/notify/delete/{n}', 'DashboardController@notifyAlert');
Route::post('/login', 'LoginController@login');
Route::get('/cadastro', 'LoginController@cadastro');
Route::post('/cadastro', 'LoginController@salvaCadastro');
Route::get('/esqueciSenha', 'LoginController@esqueciSenha')->name('esqueci-minha-senha');
Route::post('/esqueciSenha', 'LoginController@esqueciSenhaPost');

Route::get('/404', function(){
	return view('404');
});

Route::middleware(['validaLogado', 'validaAcesso', 'validaPagamento', 'validaContribuicao'])->group(function () {

	Route::get('/dashboard', 'DashboardController@index');

	Route::post('/updateNiver', 'DashboardController@updateNiver')->name('dashboard.updateNiver');

	Route::group(['prefix' => 'perfil'], function(){
		Route::get('/', 'DashboardController@perfil');
		Route::post('/salvarFoto', 'DashboardController@salvarFoto');
		Route::post('/update', 'DashboardController@update');
		Route::get('/cronogramaPresenca', 'DashboardController@cronogramaPresenca');

	});

	Route::group(['prefix' => 'aluno'], function(){
		Route::get('/', 'AlunoController@index')->name('aluno.index');
		Route::get('/new', 'AlunoController@new');
		Route::get('/edit/{id}', 'AlunoController@edit')->name('aluno.edit');
		Route::get('/editAtivar/{id}', 'AlunoController@editAtivar')->name('aluno.editAtivar');
		Route::get('/note/{id}', 'AlunoController@note')->name('aluno.note');
		Route::get('/push/{id}', 'AlunoController@push')->name('aluno.push');
		Route::get('/detail/{id}', 'AlunoController@detail')->name('aluno.detail');
		Route::get('/historic/{id}', 'AlunoController@historic')->name('aluno.historic');
		Route::get('/boletin/{id}', 'AlunoController@boletin')->name('aluno.boletin');
		Route::post('/boletinStore', 'AlunoController@boletinStore')->name('aluno.boletinStore');
		Route::post('/delete/{id}', 'AlunoController@delete')->name('aluno.delete');
		Route::post('/deleteDash/{id}', 'AlunoController@deleteDash')->name('aluno.deleteDash');
		Route::post('/store', 'AlunoController@store')->name('aluno.store');
		Route::put('{id}/update', 'AlunoController@update')->name('aluno.update');
		Route::put('{id}/storeNote', 'AlunoController@storeNote')->name('aluno.storeNote');
		Route::put('{id}/alterarSenha', 'AlunoController@alterarSenha')->name('aluno.alterar-senha');
		Route::put('{id}/pushPut', 'AlunoController@pushPut')->name('aluno.push-put');

		Route::get('/acessosLogin/{id}', 'AlunoController@acessosLogin');
		Route::get('/acessosPosicao/{id}', 'AlunoController@acessosPosicao');

		Route::get('/mensalidades/{id}', 'AlunoController@mensalidades');
		Route::get('/contribuicoes/{id}', 'AlunoController@contribuicoes');
		
		Route::delete('{id}/deleteBoletin', 'AlunoController@deleteBoletin')->name('aluno.deleteBoletin');

	});

	Route::group(['prefix' => 'categorias'], function(){
		Route::get('/', 'CategoriaController@index')->name('categoria.index');
		Route::get('/new', 'CategoriaController@new');
		Route::get('/edit/{id}', 'CategoriaController@edit')->name('categoria.edit');
		Route::post('/delete/{id}', 'CategoriaController@delete')->name('categoria.delete');
		Route::post('/store', 'CategoriaController@store')->name('categoria.store');
		Route::put('{id}/update', 'CategoriaController@update')->name('categoria.update');
	});

	Route::group(['prefix' => 'recompensas'], function(){
		Route::get('/', 'RecompensaController@index')->name('recompensa.index');
		Route::get('/new', 'RecompensaController@new');
		Route::get('/edit/{id}', 'RecompensaController@edit')->name('recompensa.edit');
		Route::post('/delete/{id}', 'RecompensaController@delete')->name('recompensa.delete');
		Route::post('/store', 'RecompensaController@store')->name('recompensa.store');
		Route::put('{id}/update', 'RecompensaController@update')->name('recompensa.update');

		Route::get('/alunos', 'RecompensaController@alunoLogin');
		Route::get('/historico', 'RecompensaController@historico');

	});

	Route::group(['prefix' => 'posicao'], function(){
		Route::get('/', 'PosicaoController@index')->name('posicao.index');
		Route::get('/new', 'PosicaoController@new');
		Route::get('/edit/{id}', 'PosicaoController@edit')->name('posicao.edit');
		Route::get('/view/{id}', 'PosicaoController@view')->name('posicao.view');
		Route::get('/push/{id}', 'PosicaoController@push')->name('posicao.push');
		Route::post('/delete/{id}', 'PosicaoController@delete')->name('posicao.delete');
		Route::post('/deleteVideo/{id}', 'PosicaoController@deleteVideo')->name('posicao.delete-video');
		Route::post('/store', 'PosicaoController@store')->name('posicao.store');
		Route::put('{id}/update', 'PosicaoController@update')->name('posicao.update');
		Route::put('{id}/newVideo', 'PosicaoController@newVideo')->name('posicao.new-video');
		Route::put('{id}/newComent', 'PosicaoController@newComent')->name('posicao.new-coment');

		Route::get('/video', 'PosicaoController@video');;
		Route::put('{id}/video_new_manual', 'PosicaoController@videoNewManual')->name('posicao.new-video-manual');
		Route::get('/respostas', 'PosicaoController@respostas');
		Route::put('{id}/put_coment', 'PosicaoController@putComent')->name('posicao.put-coment');

		Route::delete('{id}/respostaDestroy', 'PosicaoController@respostaDestroy')->name('posicao.resposta-destroy');



	});

	Route::group(['prefix' => 'config'], function(){
		Route::get('/', 'ConfigController@index')->name('config.index');
		Route::post('/store', 'ConfigController@store')->name('config.store');
	});

	Route::group(['prefix' => 'agenda'], function(){
		Route::get('/', 'AgendaController@index')->name('agenda.index');
		Route::get('/new', 'AgendaController@new')->name('agenda.new');
		Route::get('/edit/{id}', 'AgendaController@edit')->name('agenda.edit');
		Route::post('/delete/{id}', 'AgendaController@delete')->name('agenda.delete');
		Route::put('{id}/update', 'AgendaController@update')->name('agenda.update');

		Route::post('/store', 'AgendaController@store')->name('agenda.store');

	});

	Route::group(['prefix' => 'treino'], function(){
		Route::get('/confirmarPresenca/{treino_id}', 'TreinoController@confirmarPresenca');
		Route::get('/confirmarTreino', 'TreinoController@confirmarTreino');
		Route::get('/desmarcarTreino', 'TreinoController@desmarcarTreino');
		Route::get('/reconfirmar', 'TreinoController@reconfirmar');
	});

	Route::group(['prefix' => 'cronograma'], function(){
		Route::get('/', 'TreinoController@index');

	});

	Route::group(['prefix' => 'presenca'], function(){
		Route::get('/', 'PresencaController@index');
		Route::get('/alunos/{treino_id}', 'PresencaController@alunos');
		Route::get('/alunosPresentes/{treino_id}', 'PresencaController@alunosPresentes');
		Route::get('/confirmarAluno/{aluno_treino_id}', 'PresencaController@confirmarAluno');
		Route::get('/manual', 'PresencaController@manual');
		Route::post('/manualStore', 'PresencaController@manualStore')->name('presenca.store');

	});

	Route::group(['prefix' => 'graduacao'], function(){
		Route::get('/', 'GraduacaoController@index')->name('graduacao.index');
		Route::get('/new', 'GraduacaoController@new');
		Route::post('/delete/{id}', 'GraduacaoController@delete')->name('graduacao.delete');

		Route::post('/store', 'GraduacaoController@store')->name('graduacao.store');

	});

	Route::group(['prefix' => 'exames'], function(){
		Route::get('/', 'ExameController@index')->name('exame.index');
		Route::get('/new', 'ExameController@new');
		Route::get('/edit/{id}', 'ExameController@edit')->name('exame.edit');
		Route::post('/delete/{id}', 'ExameController@delete')->name('exame.delete');
		Route::put('{id}/update', 'ExameController@update')->name('exame.update');

		Route::post('/store', 'ExameController@store')->name('exame.store');
		Route::put('{id}/update', 'ExameController@update')->name('exame.update');
		Route::get('/view/{id}', 'ExameController@view')->name('exame.view');
		Route::put('/includeAluno/{id}', 'ExameController@includeAluno')->name('exame.includeAluno');

	});

	Route::group(['prefix' => 'exames-aluno'], function(){
		Route::get('/', 'ExameAlunoController@index')->name('exame-aluno.index');
		Route::post('/delete/{id}', 'ExameAlunoController@delete')->name('exame-aluno.delete');
		Route::get('/start/{id}', 'ExameAlunoController@start')->name('exame-aluno.start');
		Route::get('/alterarStatus/{id}', 'ExameAlunoController@alterarStatus')->name('exame-aluno.alterarStatus');
		Route::get('/finalizar/{id}', 'ExameAlunoController@finalizar')->name('exame-aluno.finalziar');
		Route::put('{id}/finalizar', 'ExameAlunoController@finalizarPut')->name('exame-aluno.finalizar');

	});

	Route::group(['prefix' => 'mensalidade'], function(){
		Route::get('/', 'MensalidadeController@index')->name('mensalidade.index');
		Route::get('/new', 'MensalidadeController@new');
		Route::get('/pendentes', 'MensalidadeController@pendentes');
		Route::get('/edit/{id}', 'MensalidadeController@edit')->name('mensalidade.edit');
		Route::post('/delete/{id}', 'MensalidadeController@delete')->name('mensalidade.delete');
		Route::post('/deleteCheckout/{id}', 'MensalidadeController@deleteCheckout')->name('mensalidade.deleteCheckout');
		Route::put('{id}/update', 'MensalidadeController@update')->name('mensalidade.update');

		Route::post('/store', 'MensalidadeController@store')->name('mensalidade.store');
		Route::get('/consultaPIX', 'MensalidadeController@consultaPIX');
		Route::get('/setPlano/{id}', 'MensalidadeController@setPlano')->name('mensalidade.setPlano');
		

	});

	Route::group(['prefix' => 'aviso'], function(){
		Route::get('/', 'AvisoController@index')->name('aviso.index');
		Route::get('/new', 'AvisoController@new');
		Route::get('/edit/{id}', 'AvisoController@edit')->name('aviso.edit');
		Route::get('/view/{id}', 'AvisoController@view')->name('aviso.view');
		Route::post('/delete/{id}', 'AvisoController@delete')->name('aviso.delete');
		Route::put('{id}/update', 'AvisoController@update')->name('aviso.update');

		Route::post('/store', 'AvisoController@store')->name('aviso.store');
		Route::put('{id}/update', 'AvisoController@update')->name('aviso.update');

	});

	Route::group(['prefix' => 'loja'], function(){
		Route::get('/produtos', 'LojaController@produtos')->name('loja.produtos');
		Route::get('/produto_detalhe/{id}', 'LojaController@produto_detalhe')->name('loja.produto_detalhe');
		Route::post('/add_item_carrinho', 'LojaController@add_item_carrinho')->name('loja.add_item_carrinho');
		Route::get('/carrinho', 'LojaController@carrinho')->name('loja.carrinho');
		Route::delete('{id}/delete_item', 'LojaController@delete_item')->name('loja.delete_item');
		Route::get('/carrinho', 'LojaController@carrinho')->name('loja.carrinho');
		Route::get('/finalizar', 'LojaController@finalizar')->name('loja.finalizar');
		Route::get('/pedido_encerreado/{tracation_id}', 'LojaController@pedido_encerreado')->name('loja.pedido_encerreado');

		Route::post('/pagamentoBoleto', 'LojaController@pagamentoBoleto')->name('loja.pagamento-boleto');
		Route::post('/pagamentoPix', 'LojaController@pagamentoPix')->name('loja.pagamento-pix');
		Route::post('/pagamentoCartao', 'LojaController@pagamentoCartao')->name('loja.pagamento-cartao');

		Route::get('/pedidos', 'LojaController@pedidos')->name('loja.pedidos');
		Route::get('/detalhePedido/{id}', 'LojaController@detalhePedido')->name('loja.detalhes_pedido');
		
	});

	Route::resource('boletin', 'BoletinController');

	Route::resource('categoria-produtos', 'CategoriaProdutoController');
	Route::resource('produtos', 'ProdutoController');
	Route::resource('escolas', 'EscolaController');
	Route::get('/listAlunos', 'EscolaController@listAlunos')->name('escolas.listAlunos');
	Route::get('/contribuicaoRetirar', 'ContribuicaoController@retirar')->name('contribuicao.retirar');
	Route::get('/contribuicaoPendente', 'ContribuicaoController@pendentes')->name('contribuicao.pendentes');
	Route::get('/consulta', 'ContribuicaoController@consulta')->name('contribuicao.payments_consult');
	Route::post('/contribuicaoRetirarStore', 'ContribuicaoController@retirarStore')->name('contribuicao.retirar-store');
	Route::post('/contribuicaoUpdateDate', 'ContribuicaoController@updateDate')->name('contribuicao.editData');
	Route::delete('{id}/contribuicaoRetirarDelete', 'ContribuicaoController@contribuicaoRetirarDelete')->name('contribuicaoRetirar.delete');

	Route::get('/produtos/{id}/galery', 'ProdutoController@galery')->name('produtos.galery');
	Route::delete('/produtos/{id}/destroy_image', 'ProdutoController@destroy_image')->name('produtos.destroy_image');
	Route::put('/produtos/{id}/upload_image', 'ProdutoController@upload_image')->name('produtos.upload_image');

	Route::resource('pedidos', 'PedidoController');
	Route::get('/consultaPagamentos', 'PedidoController@consultaPagamentos')->name('pedidos.consulta_pagamentos');
	Route::get('/carrinhos', 'PedidoController@carrinhos')->name('pedidos.carrinhos');

	Route::get('/novasFaixas', function (){
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
	});

});

Route::middleware(['validaLogado', 'validaAcesso'])->group(function () {
	Route::resource('contribuicao', 'ContribuicaoController');
	
	Route::group(['prefix' => 'pagamento'], function(){
		Route::get('/', 'PagamentoController@index')->name('pagamento.index');
		Route::post('/pay', 'PagamentoController@pay')->name('pagamento.pay');
	});
});


