<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{getenv("APP_NAME")}} Registro</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">

                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Cadastro de aluno!</h1>
                            </div>
                            <form class="" method="post" action="">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user @if($errors->has('nome')) is-invalid @endif" name="nome" 
                                        placeholder="Nome" value="{{old('nome')}}">
                                        @if($errors->has('nome'))
                                        <span class="text-danger ml-2">{{ $errors->first('nome') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user @if($errors->has('sobre_nome')) is-invalid @endif" name="sobre_nome" 
                                        placeholder="Sobre nome" value="{{old('sobre_nome')}}">
                                        @if($errors->has('sobre_nome'))
                                        <span class="text-danger ml-2">{{ $errors->first('sobre_nome') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user @if($errors->has('email')) is-invalid @endif" name="email" 
                                    placeholder="Email" value="{{old('email')}}">
                                    @if($errors->has('email'))
                                    <span class="text-danger ml-2">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user @if($errors->has('senha')) is-invalid @endif" name="senha" placeholder="Senha" value="{{old('senha')}}">
                                        @if($errors->has('senha'))
                                        <span class="text-danger ml-2">{{ $errors->first('senha') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user @if($errors->has('senha')) is-invalid @endif" name="repita_senha" placeholder="Repita senha" value="{{old('repita_senha')}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input data-mask="00 00000-0000" data-mask-reverse="true" type="tel" class="form-control form-control-user @if($errors->has('celular')) is-invalid @endif" name="celular" placeholder="Celular" value="{{old('celular')}}">
                                        @if($errors->has('celular'))
                                        <span class="text-danger ml-2">{{ $errors->first('celular') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <select id="sexo" name="sexo" class="form-control select-control @if($errors->has('sexo')) is-invalid @endif">
                                            <option value="">Sexo</option>
                                            <option @if(old('sexo') == 'f') selected @endif value="f">Feminino</option>
                                            <option @if(old('sexo') == 'm') selected @endif value="m">Masculino</option>
                                        </select>
                                        @if($errors->has('sexo'))
                                        <span class="text-danger ml-2">{{ $errors->first('sexo') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input data-mask="00,00" data-mask-reverse="true" type="tel" class="form-control form-control-user @if($errors->has('peso_atual')) is-invalid @endif" name="peso_atual" placeholder="Peso atual" value="{{old('peso_atual')}}">
                                        @if($errors->has('peso_atual'))
                                        <span class="text-danger ml-2">{{ $errors->first('peso_atual') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="cidade_id" class="form-control select-control @if($errors->has('cidade_id')) is-invalid @endif">
                                            <option value="">Cidade</option>
                                            @foreach($cidades as $c)
                                            <option @if(old('cidade_id') == $c->id) selected @endif value="{{$c->id}}">{{$c->nome}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('cidade_id'))
                                        <span class="text-danger ml-2">{{ $errors->first('cidade_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input data-mask="00/00/0000" data-mask-reverse="true" type="tel" class="form-control form-control-user @if($errors->has('data_nascimento')) is-invalid @endif" name="data_nascimento" placeholder="Data de nascimento" value="{{old('data_nascimento')}}">
                                        @if($errors->has('data_nascimento'))
                                        <span class="text-danger ml-2">{{ $errors->first('data_nascimento') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row d-none div-mod">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Modalidade</label>
                                        <br>
                                        <input @if(old('mod_jiu') == 'on') checked @endif type="checkbox" name="mod_jiu"> Jiu-jitsu
                                        <input @if(old('mod_def') == 'on') checked @endif class="ml-2" type="checkbox" name="mod_def"> Defesa pessoal
                                        @if($errors->has('modalidade'))
                                        <span class="text-danger ml-2">{{ $errors->first('modalidade') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Cadastrar
                                </button>

                                @if(session()->has('flash_sucesso'))
                                <div class="alert alert-custom alert-success fade show mt-2" role="alert">

                                    <div class="alert-text">{{ session()->get('flash_sucesso') }}</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                                @endif

                                @if(session()->has('flash_erro'))
                                <div class="alert alert-custom alert-danger fade show mt-2" role="alert">

                                    <div class="alert-text">{{ session()->get('flash_erro') }}</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                                @endif

                                <hr>
                            </form>
                            <div class="text-center">
                                <br>
                            </div>
                            <div class="text-center">
                                <a class="small" href="/login">Já tem cadastro? voltar para Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
    <script type="text/javascript" src="/js/jquery.mask.min.js"></script>

    <script type="text/javascript">
        $(function(){
            validaModalidade()
        })

        $('#sexo').change(() => {
            validaModalidade()
        })

        function validaModalidade(){
            let sexo = $('#sexo').val()
            if(sexo == 'f'){
                $('.div-mod').removeClass('d-none')
            }else{
                $('.div-mod').addClass('d-none')
            }
        }
    </script>

</body>

</html>