<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="/images/pezzo.png">
    <title>Área de Acesso {{getenv("APP_NAME")}}</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">

                            @if(session()->has('flash_sucesso'))
                            <div class="alert alert-custom alert-success fade show mt-2 w-100" role="alert">

                                <div class="alert-text">{{ session()->get('flash_sucesso') }}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            @endif

                            @if(session()->has('flash_erro'))
                            <div class="alert alert-custom alert-danger fade show mt-2 w-100" role="alert">

                                <div class="alert-text">{{ session()->get('flash_erro') }}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            @endif

                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">{{getenv("APP_NAME")}}</h1>
                                        <h4 class="h6 text-gray-900 mb-4">Bem vindo!</h4>
                                    </div>
                                    <form class="user" method="post" action="">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                            id="exampleInputEmail" autofocus aria-describedby="emailHelp"
                                            placeholder="Email ou celular" @if(session('login') != null) value="{{ session('login') }}" @else @if(isset($loginCookie)) value="{{$loginCookie}}" @endif @endif name="login">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" name="senha" placeholder="Senha" autocomplete="off" @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input @isset($lembrarCookie) @if($lembrarCookie == true) checked @endif @endif name="lembrar" type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Lembrar-me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>

                                        <hr>
                                        <br>
                                        <br>
                                        <br>
                                    </form>
                                    <hr>
                                    
                                    <div class="text-center">
                                        <a class="small" href="/cadastro">Primeiro acesso? clique aqui!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{route('esqueci-minha-senha')}}">Esqueceu a senha?</a>
                                    </div>
                                </div>
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

</body>

</html>