<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/images/pezzo.png">
    
    <title>{{$title}}</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"
    integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">

                <div class="sidebar-brand-text mx-3">
                    {{getenv("APP_NAME")}}
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="la la-tachometer-alt la-menu"></i>
                    <span>Home</span>
                </a>
            </li>

            
            @if(session('user_logged')['master'])
            <li class="nav-item">
                <a class="nav-link" href="/aluno">
                    <i class="la la-user-graduate la-menu"></i>
                    <span>Alunos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/mensalidade">
                    <i class="la la-money-bill la-menu"></i>
                    <span>Mensalidades</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/aviso">
                    <i class="la la-bell la-menu"></i>
                    <span>Avisos</span>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="la la-university la-menu"></i>
                <span>Posições</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @if(session('user_logged')['master'])
                    <a class="collapse-item" href="/categorias">Categorias</a>
                    @endif
                    @if(session('user_logged')['master'] || session('user_logged')['cadastro_posicao'] == 1)
                    <a class="collapse-item" href="/posicao/new">Nova posição</a>
                    @endif
                    <a class="collapse-item" href="/posicao">Lista de posições</a>
                </div>
            </div>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTreino"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="la la-chess-knight la-menu"></i>
                <span>Treino</span>
            </a>

            <div id="collapseTreino" class="collapse" aria-labelledby="headingUtilities"data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/agenda">Agenda</a>
                    
                    @if(session('user_logged')['master'])
                    <a class="collapse-item" href="/cronograma">Cronograma</a>
                    <a class="collapse-item" href="/presenca/">Confirmar presença</a>
                    @endif
                </div>
            </div>

            
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGraduacao"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="la la-graduation-cap la-menu"></i>
                <span>Graduação</span>
            </a>

            <div id="collapseGraduacao" class="collapse" aria-labelledby="headingUtilities"data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @if(session('user_logged')['master'])
                    <a class="collapse-item" href="/graduacao">Listar</a>
                    <a class="collapse-item" href="/graduacao/new">Graduar aluno</a>
                    <a class="collapse-item" href="/recompensas">Recompensas</a>
                    <a class="collapse-item" href="/exames">Exames de faixa</a>
                    
                    @endif

                    @if(!session('user_logged')['master'])
                    <a class="collapse-item" href="/recompensas/alunos">Recompensas</a>
                    <a class="collapse-item" href="/recompensas/historico">Histórico</a>
                    @endif
                    @if(session('user_logged')['master'])
                    <a class="collapse-item" href="/exames-aluno">Exames atribuidos</a>
                    @endif

                </div>
            </div>

            @if(!session('user_logged')['master'])
            <li class="nav-item">
                <a class="nav-link" href="/pagamento">
                    <i class="la la-money-bill la-menu"></i>
                    <span>Pagamento</span>
                </a>
            </li>
            @endif


            <li class="nav-item">
                <a class="nav-link" href="{{ route('contribuicao.index') }}">
                    <i class="la la-hand-holding-usd la-menu"></i>
                    <span>Contribuição</span>
                </a>
            </li>

            @if(session('user_logged')['master'])
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLoja"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="la la-store la-menu"></i>
                <span>Configuração Loja</span>
            </a>

            <div id="collapseLoja" class="collapse" aria-labelledby="headingUtilities"data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item" href="{{ route('categoria-produtos.index') }}">Categorias</a>
                    <a class="collapse-item" href="{{ route('produtos.index') }}">Produtos</a>
                    <a class="collapse-item" href="{{ route('pedidos.index') }}">Pedidos</a>
                    
                </div>
            </div>
            @endif


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseConfigLoja"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="la la-shopping-bag la-menu"></i>
                <span>Loja</span>
            </a>

            @if(isset(session('user_logged')['is_boletin']))
            <li class="nav-item">
                <a class="nav-link" href="/boletin">
                    <i class="la la-school la-menu"></i>
                    <span>Boletim</span>
                </a>
            </li>
            @endif

            <div id="collapseConfigLoja" class="collapse" aria-labelledby="headingUtilities"data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item" href="/loja/produtos">Produtos</a>
                    <a class="collapse-item" href="/loja/pedidos">Meus Pedidos</a>
                    
                </div>
            </div>

        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <form
                class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="get" action="/posicao">
                <div class="input-group">
                    <input name="search" type="text" class="form-control bg-light border-0 small" placeholder="Pesquisar posição"
                    aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>

                </div>
                
            </form>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form method="get" action="/posicao" class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input name="search" type="text" class="form-control bg-light border-0 small"
                                placeholder="Pesquisar posição" aria-label="Search"
                                aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                @if($faixaImage != "")
                <img style="width: 120px; height: 40px; border-radius: 10px; margin-top: 10px;" src="/faixas/{{ $faixaImage }}.png">
                @endif

                @if($itensCarrinho > 0)
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle"  href="/loja/carrinho">
                        <i class="fas fa-shopping-cart fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">{{ $itensCarrinho }}</span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    
                </li>
                @endif
                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">{{sizeof($alertas)}}</span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    @if(sizeof($alertas) > 0)
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            Alertas
                        </h6>

                        @foreach($alertas as $a)

                        <a class="dropdown-item d-flex align-items-center" @isset($a['link']) href="{{$a['link']}}" @endif>
                            <div class="mr-3">
                                @isset($a['remove'])
                                <div onclick="removeNotificacao('{{$a["aviso_master"]}}')"  class="icon-circle bg-danger">
                                    <i class="fas fa-remove text-white"></i>
                                </div>
                                @else
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                @endif

                            </div>
                            <div>
                                <div class="small text-gray-500">{{$a['data']}}</div>
                                <span class="font-weight-bold">{{$a['mensagem']}}</span>

                            </div>

                        </a>

                        @endforeach
                        
                    </div>
                    @endif
                </li>

                

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ session('user_logged')['aluno']->nome }}</span>
                        @if(session('user_logged')['aluno']->imagem == "")
                        <img class="img-profile rounded-circle"
                        src="/img/undraw_profile.svg">
                        @else
                        <img class="img-profile rounded-circle"
                        src="/alunos/{{session('user_logged')['aluno']->imagem}}">
                        @endif
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/perfil">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Perfil
                        </a>

                        @if(session('user_logged')['master'])
                        <a class="dropdown-item" href="/config">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Configurações
                        </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logoff">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Sair
                        </a>
                    </div>
                </li>
            </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="ml-2 mr-2">

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
            @yield('content')

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Pezzo by Slym {{date('Y')}}</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt-BR.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>
<script type="text/javascript" src="/js/jquery.mask.min.js"></script>

<script src="/js/main.js"></script>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>


@yield('js')
</body>

</html>