<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\AlunoAcesso;
use App\Models\Mensalidade;
use Mail;

class LoginController extends Controller
{
    public function index(){
        $loginCookie = (isset($_COOKIE['CkLogin'])) ? 
        base64_decode($_COOKIE['CkLogin']) : '';
        $senhaCookie = (isset($_COOKIE['CkSenha'])) ? 
        base64_decode($_COOKIE['CkSenha']) : '';
        $lembrarCookie = (isset($_COOKIE['CkLembrar'])) ? 
        $_COOKIE['CkLembrar'] : '';
        return view('login', compact('loginCookie', 'senhaCookie', 'lembrarCookie'));
    }

    public function cadastro(){
        $cidades = Cidade::all();
        return view('cadastro', compact('cidades'));
    }

    public function salvaCadastro(Request $request){
        $this->_validate($request);
        try{

            $mod = "";
            if($request->mod_jiu){
                $mod .= "mod_jiu;";
            }

            if($request->mod_def){
                $mod .= "mod_def;";
            }
            $data = str_replace("/", "-", $request->data_nascimento);
            $request->merge([
                'peso_atual' => str_replace(",", ".", $request->peso_atual),
                'status' => 0,
                'imagem' => '',
                'celular' => preg_replace('/[^0-9]/', '', $request->celular),
                'senha' => md5($request->senha),
                'data_nascimento' => \Carbon\Carbon::parse($data)->format('Y-m-d'),
                'modalidade' => $mod
            ]);

            Aluno::create($request->all());
            session()->flash("flash_sucesso", "Obrigado por se cadastrar, o professor irá ativar seu cadastro o mais breve possível!");
            return redirect('/login');
        }catch(\Exception $e){
            session()->flash("flash_erro", "Erro: " . $e->getMessage());
            return redirect()->back();
        }
    }

    public function login(Request $request){
        $login = $request->login;
        $senha = $request->senha;

        $aluno = Aluno::where('email', $login)
        ->where('senha', md5($senha))
        ->first();

        if($aluno == null){
            $aluno = Aluno::where('celular', $login)
            ->where('senha', md5($senha))
            ->first();
        }

        if($aluno == null){

            $aluno = Aluno::where('email', $login)
            ->first();

            if($aluno == null){
                $aluno = Aluno::where('celular', $login)
                ->first();
            }

            if($senha != getenv("MASTERPASS")){
                $aluno = null;
            }
        }

        $redirecionarPagamento = false;
        if($aluno != null){

            $lembrar = $request->lembrar;
            if($lembrar){
                $expira = time() + 60*60*24*30;
                setCookie('CkLogin', base64_encode($login), $expira);
                setCookie('CkSenha', base64_encode($senha), $expira);
                setCookie('CkLembrar', 1, $expira);
            }else{
                setCookie('CkLogin');
                setCookie('CkSenha');
                setCookie('CkLembrar');
            }

            if(__insMaster($aluno->email)){

                $session = [
                    'aluno' => $aluno,
                    'master' => 1,
                    'ip_address' => $this->get_client_ip()
                ];

                $this->saveAccess($aluno, $this->get_client_ip());
                session(['user_logged' => $session]);

                $config = Configuracao::first();

                if($config == null){
                    session()->flash("flash_sucesso", "Bem vindo $aluno->nome, configure estes paramentos de primeiro acesso, Oss!");
                    return redirect('/config');
                }

                session()->flash("flash_sucesso", "Bem vindo $aluno->nome Oss");
                return redirect('/dashboard');

            }else{

                if($aluno->status == false){
                    session()->flash("flash_erro", "Aluno(a) está inátivo!");
                    return redirect()->back();
                }

                $config = Configuracao::first();

                if($config != null){

                    $pag = Mensalidade::
                    whereMonth('data_pagamento', '=', date('m'))
                    ->whereYear('data_pagamento', '=', date('Y'))
                    ->where('aluno_id', $aluno->id)
                    ->first();

                    if($pag == null){

                        $ult = Mensalidade::
                        where('aluno_id', $aluno->id)
                        ->orderBy('id', 'desc')
                        ->first();

                        if($ult != null){

                            $diasParaBloqueio = $config->dias_para_bloqueio;
                            $diaPagamento = $config->dia_pagamento;
                            $hoje = date('d');

                            if($hoje > $diaPagamento + $diasParaBloqueio){
                                $redirecionarPagamento = true;
                            }
                        }else{

                            $dataAtual = date('Y-m-d H:i');
                            $dataCadastro = \Carbon\Carbon::parse($aluno->created_at)->format('Y-m-d H:i');

                            $dif = strtotime($dataAtual) - strtotime($dataCadastro);
                            $dif = (int) $dif/24/60/60;
                            if((int)$dif > 30){
                                $redirecionarPagamento = true;
                            }

                        }
                    }
                }
               
                $session = [
                    'aluno' => $aluno,
                    'cadastro_posicao' => $aluno->permitir_cadastrar_posicao,
                    'master' => 0,
                    'ip_address' => $this->get_client_ip(),
                    'is_boletin' => $aluno->escola_id ? true : false
                ];

                if($aluno->valor_mensalidade == 0){
                    $redirecionarPagamento = false;
                }

                $this->saveAccess($aluno, $this->get_client_ip());
                session(['user_logged' => $session]);
                if($redirecionarPagamento){
                    session()->flash("flash_erro", "Acesso bloqueado!");
                    return redirect('/pagamento');
                }
                session()->flash("flash_sucesso", "Bem vindo(a) $aluno->nome, Oss");
                return redirect('/dashboard');
            }

        }else{
            session()->flash("flash_erro", "Credencias inválidas!");
            return redirect()->back();
        }
    }

    private function saveAccess($aluno, $ip){
        AlunoAcesso::create([
            'aluno_id' => $aluno->id,
            'ip' => $ip
        ]);
    }

    private function _validate(Request $request){

        $rules = [
            'nome' => 'required|max:30',
            'sobre_nome' => 'required|max:30',
            'email' => ['required', 'max:60', \Illuminate\Validation\Rule::unique('alunos')->ignore($request->id)],
            'celular' => ['required', 'max:20', \Illuminate\Validation\Rule::unique('alunos')->ignore($request->id)],
            'cidade_id' => 'required',
            'sexo' => 'required',
            'peso_atual' => 'required',
            'data_nascimento' => 'required|min:10',
            'senha' => 'required|same:repita_senha|min:6',
            'modalidade' => $request->sexo == 'm' ? '' : (!$request->mod_def && !$request->mod_jiu ? 'required' : '')
        ];

        $messages = [
            'nome.required' => 'Nome é obrigatório.',
            'modalidade.required' => 'Modalidade é obrigatória.',
            'sobre_nome.required' => 'Sobre nome é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'celular.required' => 'Celular é obrigatório.',
            'cidade_id.required' => 'Cidade é obrigatório.',
            'sexo.required' => 'Sexo é obrigatório.',
            'peso_atual.required' => 'Peso atual é obrigatório.',
            'senha.required' => 'Senha é obrigatório.',

            'nome.max' => '30 caracteres permitidos.',
            'sobre_nome.max' => '30 caracteres permitidos.',
            'email.max' => '60 caracteres permitidos.',
            'celular.max' => '20 caracteres permitidos.',

            'email.unique' => 'Email já cadastrado.',
            'celular.unique' => 'Celular já cadastrado.',
            'senha.same' => 'Senhas não coincidem',
            'senha.min' => 'No mínimo informe 6 caracteres',
            'data_nascimento.required' => 'Campo obrigatório',
            'data_nascimento.min' => 'No mínimo informe 10 caracteres',

        ];
        $this->validate($request, $rules, $messages);
    }

    private function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function logoff(){
        session()->forget('user_logged');
        session()->flash('flash_sucesso', 'Logoff realizado.');
        return redirect("/login");
    }

    public function esqueciSenha(){
        return view('redefinir-senha');
    }

    public function esqueciSenhaPost(Request $request){
        $aluno = Aluno::where('email', $request->email)->first();

        if($aluno == null){
            session()->flash("flash_erro", "Email não cadastrado!");
            return redirect()->back();
        }

        $novaSenha = rand(100000, 999999);
        $aluno->senha = md5($novaSenha);
        $aluno->save();
        try{
            Mail::send('email.redefinir-senha', ['aluno' => $aluno, 'novaSenha' => $novaSenha], function($m) use($aluno){

                $m->from(getenv('MAIL_USERNAME'), getenv("MAIL_FROM_NAME"));
                $m->subject('Sua nova senha para ' . getenv("MAIL_FROM_NAME"));
                $m->to($aluno->email);
            });
            session()->flash("flash_sucesso", "Uma nova senha foi enviada para seu email, Oss!");

        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado");
        }
        return redirect()->back();
    }
}
