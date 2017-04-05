<?php
define('HoorayWeb', TRUE);

include_once ("/p_settings.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Hooray</title>

        <!-- Bootstrap -->

        <link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

        <link href="/stylesheets/rangeslider.css" rel="stylesheet" type="text/css" />

        <link href="/stylesheets/styles.css" rel="stylesheet" type="text/css" />


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <!--inicio header -->

        <div class="navbar navbar-inverse " role="navigation" id="slide-nav">
            <div class="container">
                <div class="navbar-header clearfix">
                    <div class="row">
                        <div class="col-xs-7 col-sm-3">
                            <div class="row">
                                <div class="cont-toggle col-xs-3">
                                    <a class="navbar-toggle"> 
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </a>
                                </div>
                                <div class="col-xs-9 col-md-12">
                                    <a class="navbar-brand" href="/">
                                        <img src="/images/site/logo.png" alt="hooray" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 hidden-xs">
                            <form class="header-busca">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-search"></span>
                                    <input class="form-control" type="text" name="" placeholder="Equipamentos & Vestuário" />
                                </div>
                            </form>
                        </div>
                        <div class="col-xs-5">
                            <ul class="nav submenu-header">
                                <li>
                                    <a href=""><span class="glyphicon glyphicon-earphone"></span><span class="hidden-xs hidden-sm"> Atendimento</span></a>
                                </li>
                                <li>
                                    <a href="#modal-login" data-toggle="modal"><span class="glyphicon glyphicon-user"></span><span class="hidden-xs hidden-sm"> Minha conta</span></a>
                                </li>
                                <li class="submenu-header-carrinho">
                                    <a href="#modal-carrinho" data-toggle="modal"><span class="glyphicon glyphicon-shopping-cart"></span><span class="hidden-xs hidden-sm"> #03</span></a>
                                </li>
                            </ul>
                        </div>	
                    </div>
                </div>

                <div id="slidemenu" class="hidden-lg hidden-md hidden-sm">
                    
                    <form class="header-busca">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-search"></span>
                            <input class="form-control" type="text" name="" placeholder="Equipamentos & Vestuário" />
                        </div>
                    </form>
                    
                    <!-- menu responsivo --> 
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?= $_SERVER['PHP_SELF'] ?>">Home</a></li>
                        <?php
                        $menusSite = getRest($endPoint['menu']);

                        foreach ((array) $menusSite as $menu) 
                        {
                        ?>
                            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= htmlentities($menu['Descricao']) ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                <?php
                                foreach ((array) $menu['Categorias'] as $subtitulo) 
                                {
                                ?>                                                                              
                                    <li class="divider"></li>
                                    <li class="dropdown-header"><?= htmlentities($subtitulo['Descricao']) ?></li>
                            
                                    <?php
                                    foreach ((array) $subtitulo['Categorias'] as $subcategorias) 
                                    {
                                    ?>
                                    <li><a href="/categorias/<?= $subcategorias['ID'] ?>"><?= htmlentities($subcategorias['Descricao']) ?></a></li>
                                    <?php
                                    }
                                    ?>                                
                                <?php
                                }
                                ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                    <!-- menu responsivo // -->
                </div>
            </div>
            
            <!-- menu mouseover -->
            <div class="container hidden-xs hidden-sm ">
                <div class="menu-grande">
                     <ul>
                        <?php
                        foreach ((array) $menusSite as $menu) 
                        {
                        ?>
                            <li class="_dropdown">
                                <a href="#" class="dropbtn"><?= htmlentities($menu['Descricao']) ?></a>
                                <div class="dropdown-conteudo">
                                    <div class="container">
                                        <div class="row">
                                            <?php
                                            foreach ((array) $menu['Categorias'] as $subtitulo) 
                                            {
                                            ?>                                          
                                                <ul class="col-md-2">
                                                    <li><?= htmlentities($subtitulo['Descricao']) ?></li>
                                                    <?php
                                                    foreach ((array) $subtitulo['Categorias'] as $subcategorias) 
                                                    {
                                                    ?>
                                                        <li><a href="/categorias/<?= $subcategorias['ID'] ?>"><?= htmlentities($subcategorias['Descricao']) ?></a></li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="menu-grande-customize">
                                        <BR>
                                    </div>                                                                    
                                </div>
                            </li>
                        <?php    
                        }
                        ?> 
                    </ul>
                </div>
            </div>
            <!-- menu mouseover //-->
        </div>

        <?php
        $uriChamada = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
        
        $paginas = explode("/", $uriChamada);
        
        if (empty(trim($paginas[1])))
        {
            $paginas[1] = "index.php";
        }
        
        $paginas[1] = strtolower($paginas[1]);
        
        switch ($paginas[1])
        {
            case "index.php" :
                include_once ("/_pages/home.php");
                break;
            
            case "categorias" :
                $IDCategoria = $paginas[2];
                include_once ("/_pages/categorias.php");
                break;
            
            case "produtos" :
                $IDProduto = $paginas[2];
                include_once ("/_pages/produtos.php");
                break;            
            
            case "recursos" :
                $IDSessao = $paginas[2];
                include_once ("/_pages/recursos.php");
                break;                
            
            case "institucional" :
                $IDSessao = $paginas[2];
                include_once ("/_pages/recursos.php");
                break;                            

            default:
                include_once ("/_pages/404.php");
                break;
        }
        ?>

        <footer>
            <div class="container">
                <div class="row">
                    
                <?php
                $rodapeSite = getRest($endPoint['rodape']);

                foreach ((array) $rodapeSite as $rodape) 
                {
                ?>                    
                    <div class="col-md-3 col-sm-12">
                        <ul class="footer-menu">
                            <li class="hidden-xs"><?= $rodape['Descricao'] ?> </li>
                            <?php
                            foreach ((array) $rodape['Itens'] as $item)
                            {
                            ?>
                                <li><a href="<?= ($item['Ferramentas'] == 1) ? $item['Html'] : "/" . $rodape['Descricao'] . "/" . $item['ID'] ?>"<?= ($item['Ferramentas'] == 1) ? " target=\"_blank\"" : "" ?>><?= $item['Descricao'] ?></a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>

            <div class="copyright text-center">
                &copy; <?= ("2017") == date("Y") ? date("Y") : "2017-" . date("Y") ?> HOORAY | Todos os direitos reservados
                <br>
                <small>Endereço: Avenida Brasil, 123 - São Paulo - SP | CEP: 01010-000 | Telefone: (11) 1234-5678 | CNPJ: 01.234.567/0001-99</small>
                <div class="madein text-right">
                    Powered by <img src="/images/site/logo-invento.png" /> &nbsp;|&nbsp; Layout by <img src="/images/site/logo-barduk.png" />
                </div>
            </div>
        </footer>

        <!-- Modal -->
        <div class="modal fade modal-hooray" id="modal-carrinho" tabindex="-1" role="dialog" aria-labelledby="modal-carrinho-compras">
            <div class="modal-dialog modal-sm" role="document">

                <div class="modal-content modal-carrinho-content">
                    <div class="modal-body">
                        <div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>

                        <div class="nav">
                            CARRINHO
                        </div>

                        <ul>
                            <li>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="./images/account-pedidos-thumb.png" alt="" />	        				
                                    </div>

                                    <div class="col-xs-7">
                                        <p>
                                            Camiseta Rip Curl<br />
                                            cor: Branco<br />
                                            Tamanho: GG<br />
                                            Quantidade: 1
                                            <span>R$150,00</span>
                                        </p>
                                    </div>

                                    <div class="col-xs-2 text-right">		        				
                                        <a href="#"><span aria-hidden="true">&times;</span></a>
                                    </div>
                                </div>

                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="./images/account-pedidos-thumb.png" alt=""/>	        				
                                    </div>

                                    <div class="col-xs-7">
                                        <p>
                                            Camiseta Rip Curl<br />
                                            cor: Branco<br />
                                            Tamanho: GG<br />
                                            Quantidade: 1
                                            <span>R$150,00</span>
                                        </p>
                                    </div>

                                    <div class="col-xs-2 text-right">		        				
                                        <a href="#"><span aria-hidden="true">&times;</span></a>
                                    </div>
                                </div>

                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="./images/account-pedidos-thumb.png" alt=""/>	        				
                                    </div>

                                    <div class="col-xs-7">
                                        <p>
                                            Camiseta Rip Curl<br />
                                            cor: Branco<br />
                                            Tamanho: GG<br />
                                            Quantidade: 1
                                            <span>R$150,00</span>
                                        </p>
                                    </div>

                                    <div class="col-xs-2 text-right">		        				
                                        <a href="#"><span aria-hidden="true">&times;</span></a>
                                    </div>
                                </div>

                            </li>
                        </ul>

                        <button type="submit" class="btn btn-primary btn-lg">CHECKOUT</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade modal-hooray" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modal-efetuar-login">

            <div class="modal-dialog modal-sm" role="document">

                <div class="modal-content modal-login-content">
                    <div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <div class="nav">
                            <ul role="tablist">
                                <li class="active">
                                    <a href="#tab-login" aria-controls="tab-cadastre" role="tab" data-toggle="tab">FAÇA LOGIN</a>	
                                </li>
                                <li>
                                    <a href="#tab-cadastre" aria-controls="tab-cadastre" role="tab" data-toggle="tab">CADASTRE-SE</a>
                                </li>
                            </ul>


                        </div>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tab-login">
                                <form>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" name="" placeholder="E-mail">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="" placeholder="Senha">	
                                                <a href="#tab-esqueci" aria-controls="tab-cadastre" role="tab" data-toggle="tab">Esqueci a senha</a>

                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg">ENTRAR</button>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab-cadastre">
                                <form>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" name="" placeholder="Nome">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="" placeholder="E-mail">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="" placeholder="Senha">	
                                                <a href="#"></a>


                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg">ENTRAR</button>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab-esqueci">
                                <h4>Esqueci a senha</h4>
                                <form>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" name="" placeholder="Seu e-mail">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg">Enviar</button>
                                    <p class="text-left"><a href="#tab-login" aria-controls="tab-cadastre" role="tab" data-toggle="tab">Voltar</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="javascripts/bootstrap.min.js"></script>
        <script src="javascripts/rangeslider.js"></script>
        <script src="javascripts/util.js"></script>
    </body>
</html>