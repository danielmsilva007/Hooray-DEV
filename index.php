<?php
define('HoorayWeb', TRUE);

include_once ("p_settings.php");

$phpPost = filter_input_array(INPUT_POST);

session_start();

if (!empty($phpPost['logoff']) && $phpPost['logoff'] == md5("logoff")) // se logoff solicitado, finaliza a sessão e recarrega a pagina
{
    session_destroy();
    Header ("Location: " . URLSite);
}

if (!empty($_SESSION['bearer'])) // obtem dados dos cliente a partir do token da sessão
{
    $dadosLogin = login($endPoint['login'], $_SESSION['bearer']);
    
    if (empty($dadosLogin['ID']))
    {
        session_destroy();
        $dadosLogin = ['ID' => -1];
    }
    else
    {
        if (!empty($_SESSION['carrinho'])) // se houver carrinho na sessão, o associa ao login do usuario.
        {
            $dadosLoginCarrinhoLogin = ["CarrinhoID" => $_SESSION['carrinho'],
                                        "LoginID" => $dadosLogin['ID']
                ];

            $associarCarrinhoLogin = sendRest($endPoint['addlogincarrinho'], $dadosLoginCarrinhoLogin, "PUT");

            if ($associarCarrinhoLogin == true) // se assicoação ocorrer com sucesso, gravar o ID do novo carrinho e apaga a session.
            {
                $dadosLogin['CarrinhoId'] = $_SESSION['carrinho'];
                unset($_SESSION['carrinho']);
            }
        }
    }
}
else
{
    $dadosLogin = ['ID' => -1];
}

if (!empty($dadosLogin['CarrinhoId']))
{
    $numCarrinho = $dadosLogin['CarrinhoId'];
}
elseif (!empty($_SESSION['carrinho']))
{
    $numCarrinho = $_SESSION['carrinho'];
} 
else
{
    $numCarrinho = '';
}

$URISite = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);

$uriTratada = explode("?", $URISite);

$paginas = explode("/", $uriTratada[0]);

if (empty(trim($paginas[1])))
{
    $paginas[1] = "index.php";
}

$paginas[1] = strtolower($paginas[1]);
$paginas[1] = str_replace(" ", "", $paginas[1]);

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
        <link href="./javascripts/nouislider.css" rel="stylesheet" type="text/css" />
        <link href="/stylesheets/styles.css" rel="stylesheet" type="text/css" />
        <script src="/javascripts/jquery-3.1.1.js"></script>
        <script src="/javascripts/jquery.maskedinput.js"></script>
        <link rel="stylesheet" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <?= ($paginas[1] == "checkout" && $dadosLogin['ID'] > 0) ? '<script type="text/javascript" src="//assets.moip.com.br/v2/moip.min.js"></script>' : '' ?>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="shortcut icon" href="favicon.ico">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114.png">
        
        <script type="text/javascript">
            function obterBearer()
            {
                $('#resultBearer').html('Autenticando...');
                
                var loginEmail = $('#loginEmail').val();
                var loginSenha = $('#loginSenha').val();
                
                $.post('/_pages/login.php', {postlogin:loginEmail,postsenha:loginSenha},
                function(data)
                {
                    if (data.substring(0,2) == "!!")
                    {
                        $('#resultBearer').html(data.substring(2));
                        
                        document.loginForm.loginEmail.value = '';
                        document.loginForm.loginSenha.value = '';
                    }
                    else
                    {
                        $('#resultBearer').html(data);
                        
                        document.autForm.submit();
                    }
                });
                return false;
            }
            
            function recuperarSenha()
            {
                $('#resultRecuperarSenha').html('Solicitando nova senha...');

                var recEmail = $('#recEmail').val();

                $.post('/_pages/atualizarCadastro.php', {postemail:recEmail,
                                                         postrecsenha:'<?= md5("recuperarsenha") ?>'},
                function(dataSenha)
                {
                    $('#resultRecuperarSenha').html(dataSenha);
                    document.getElementById("recEmail").value = "";
                });
            }
            
            function retirarCarrinhoModal(IDProduto)
            {
                $('#resultDelCarrinho' + IDProduto).html('Retirando do carrinho...');
                
                $.post('/_pages/carrinhoEditar.php', {postidproduto:IDProduto,
                                                      postidcarrinho:'<?= $numCarrinho ?>',
                                                      postcarrinho:'<?= md5("editCarrinho") ?>',
                                                      posttipoedicao:'<?= md5("remover") ?>',
                                                      posttipocarrinho:'<?= md5("modal") ?>'},
                function(dataCarrinho)
                {
                    if (dataCarrinho.substring(0,2) == "!!")
                    {
                        $('#resultDelCarrinho' + IDProduto).html(dataCarrinho.substring(2));
                    }
                    else
                    {
                        $('#resultDelCarrinho' + IDProduto).html('Atualizando o carrinho...');
                        $('#qtdeCarrinho').html('&nbsp;' + dataCarrinho);
                        document.getElementById('itemCarinhoModal' + IDProduto).style.display = 'none';
                    }
                });
            }
        </script>
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
                            <form class="header-busca" name="busca1" method="get" action="/busca">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-search" onclick="document.busca1.submit();" style="cursor: pointer;"></span>
                                    <input class="form-control" type="text" name="termobusca" placeholder="o que você procura ?" required="required" />
                                </div>
                            </form>
                        </div>
                        <div class="col-xs-5">
                            <ul class="nav submenu-header">
                                <li>
                                    <a href="/contato"><span class="glyphicon glyphicon-earphone"></span><span class="hidden-xs hidden-sm"> Atendimento</span></a>
                                </li>
                                <li>
                                    <?php
                                    if (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0)
                                    {
                                        $nomeUsuario = explode(" ", $dadosLogin['Parceiro']['RazaoSocial']);
                                    ?>
                                        <a href="/minhaconta"><span class="glyphicon glyphicon-user"></span><span class="hidden-xs hidden-sm"> Olá <?= $nomeUsuario[0] ?></span></a> 
                                    <?php
                                    }
                                    else
                                    {
                                    ?>    
                                        <a href="#modal-login" data-toggle="modal" id="link-login"><span class="glyphicon glyphicon-user"></span><span class="hidden-xs hidden-sm"> Login</span></a>
                                    <?php
                                    }       
                                    ?>
                                </li>
                                <?php
                                if (!in_array($paginas[1], ['carrinho', 'checkout']))
                                {
                                ?>
                                    <li class="submenu-header-carrinho">
                                        <a href="#modal-carrinho" data-toggle="modal"><span class="glyphicon glyphicon-shopping-cart"></span><span id="qtdeCarrinho" class="hidden-xs hidden-sm"></span></a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>	
                    </div>
                </div>
                
                <div class="hidden-lg hidden-md hidden-sm">
                    <form class="header-busca" name="buscaresponsiva" method="get" action="/busca">
                        <div class="form-group">
                            <!-- <span class="glyphicon glyphicon-search" onclick="document.buscaresponsiva.submit();"></span> -->
                            <input class="form-control" type="text" name="termobusca" placeholder="o que você procura ?" required="required" />
                        </div>
                    </form>
                </div>
                
                <div id="slidemenu" class="hidden-lg hidden-md hidden-sm">
                    <!-- menu responsivo -->
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="/">Home</a></li>
                        <?php
                        $menuSite = getRest($endPoint['menu']);

                        foreach ((array) $menuSite as $secao) 
                        {
                        ?>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b> <?= htmlentities($secao['Descricao']) ?></a>
                                <ul class="dropdown-menu">
                                <?php
                                foreach ((array) $secao['Categorias'] as $categoria) 
                                {
                                ?>                                                                              
                                    <li class="divider"></li>
                                    <li><a href="/categoria?id=<?= $categoria['ID'] ?>" style="color:black;"><?= htmlentities($categoria['Descricao']) ?></a></li>
                            
                                    <?php
                                    foreach ((array) $categoria['Categorias'] as $subcategoria) 
                                    {
                                    ?>
                                        <li><a href="/categoria?id=<?= $subcategoria['ID'] ?>"><?= htmlentities($subcategoria['Descricao']) ?></a></li>
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
                        <li><a href="/marcas">Marcas</a></li>
                        <li><a href="/blog">Blog</a></li>
                    </ul>
                    <!-- menu responsivo // -->
                </div>
            </div>

            <!-- menu mouseover -->
            <div class="container hidden-xs hidden-sm ">
                <div class="menu-grande">
                    <ul>
                        <?php
                        foreach ((array) $menuSite as $secao) 
                        {
                        ?>
                            <li class="_dropdown">
                                <a href="/secao?id=<?= $secao['SecaoID'] ?>" class="dropbtn"><?= htmlentities($secao['Descricao']) ?></a>
                                <div class="dropdown-conteudo">
                                    <div class="container">
                                        <div class="row">
                                            <?php
                                            foreach ((array) $secao['Categorias'] as $categoria) 
                                            {
                                            ?>                                          
                                                <ul class="col-md-2">
                                                    <li><a href="/categoria?id=<?= $categoria['ID'] ?>" style="color:black;"><?= htmlentities($categoria['Descricao']) ?></a></li>
                                                    <?php
                                                    foreach ((array) $categoria['Categorias'] as $subcategoria) 
                                                    {
                                                    ?>
                                                        <li><a href="/categoria?id=<?= $subcategoria['ID'] ?>"><?= htmlentities($subcategoria['Descricao']) ?></a></li>
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
                                        &nbsp;
                                    </div>                                                                    
                                </div>
                            </li>
                        <?php    
                        }
                        ?> 
                        <li class="active"><a href="/marcas">Marcas</a></li>
                        <li class="active"><a href="/blog">Blog</a></li>
                    </ul>
                </div>
            </div>
            <!-- menu mouseover //-->

        </div>
        <!--fim header -->
        
        <?php
        if (!empty($phpPost['cadastroResult']) && $phpPost['cadastroResult'] == md5("cadastro")) //se efetuando cadastro, direciona para tela de cadastro.
        {
            $paginas[1] = "minhaconta";
        }
        
        switch ($paginas[1])
        {
            case "index.php" :
                include_once ("/_pages/home.php");
                break;
            
            case "contato" :
                include_once ("/_pages/contato.php");
                break;
            
            case "blog" :
                include_once ("/_pages/blog.php");
                break;
            
            case "blogpost" :
                $dadosBlog = getRest($endPoint['blog']);
                
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $IDArtigoBlog = $phpGet[array_keys($phpGet)[0]];
                }
                
                if (empty($IDArtigoBlog) || !is_numeric($IDArtigoBlog))
                {
                    include_once ("/_pages/404.php");
                }
                else
                {
                    $detalheArtigo = getRest(str_replace("{IDArtigo}", $IDArtigoBlog , $endPoint['blogartigo']));
                    
                    if (empty($detalheArtigo))
                    {
                        include_once ("/_pages/404.php");
                    }
                    else
                    {
                        include_once ("/_pages/blogPost.php");
                    }
                }
                break;            

            case "blogcategoria" :
                $dadosBlog = getRest($endPoint['blog']);
                
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $IDCategoria = $phpGet[array_keys($phpGet)[0]];
                }
                
                if (empty($IDCategoria) || !is_numeric($IDCategoria))
                {
                    include_once ("/_pages/404.php");
                }
                else
                {
                    $artigosCategoria = getRest(str_replace(["{IDBlog}", "{IDCategoria}"], [$dadosBlog['ID'], $IDCategoria], $endPoint['blogartigoscat']));
                    
                    include_once ("/_pages/blogCategoria.php");
                }
                break;
            
            case "blogbusca" :
                include_once ("/_pages/blogBusca.php");
                break;      

            case "busca" :
                $tipoBusca = "busca";
                
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $termoBusca = $phpGet[array_keys($phpGet)[0]];
                }
                include_once ("/_pages/busca.php");
                break;

            case "secao" :
                $tipoBusca = "secao";
                
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $IDSecao = $phpGet[array_keys($phpGet)[0]];                
                }
                
                if (!isset($IDSecao) || !is_numeric($IDSecao))
                {
                    include_once ("/_pages/404.php");
                }                
                else
                {
                    include_once ("/_pages/busca.php");
                }
                break;
            
            case "categoria" :
                $tipoBusca = "categoria";
                
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $IDCategoria = $phpGet[array_keys($phpGet)[0]];
                }
                
                if (!isset($IDCategoria) || !is_numeric($IDCategoria))
                {
                    include_once ("/_pages/404.php");
                }
                else
                {
                    $categoriaSite = getRest(str_replace("{IDCategoria}", $IDCategoria, $endPoint['categoria']));
                    
                    if (empty($categoriaSite))
                    {
                        include_once ("/_pages/404.php");
                    }
                    else
                    {
                        include_once ("/_pages/busca.php");
                    }
                }
                break;

            case "marca" :
                $tipoBusca = "marca";
                
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $IDMarca = $phpGet[array_keys($phpGet)[0]];
                }
                
                if (!isset($IDMarca) || !is_numeric($IDMarca))
                {
                    include_once ("/_pages/404.php");
                }
                else
                {
                    $detalheMarca = getRest(str_replace("{IDMarca}", $IDMarca, $endPoint['detalhesmarca']));
                    
                    if (empty($detalheMarca))
                    {
                        include_once ("/_pages/404.php");
                    }
                    else
                    {
                        include_once ("/_pages/busca.php");
                    }
                }
                break;                                        
                
            case "produto" :
                $phpGet = filter_input_array(INPUT_GET);
                if (!empty($phpGet))
                {
                    $IDProduto = $phpGet[array_keys($phpGet)[0]];
                }
                
                if (empty($IDProduto) || !is_numeric($IDProduto))
                {
                    include_once ("/_pages/404.php");
                }
                else
                {
                    $dadosProduto = getRest(str_replace("{IDProduto}", $IDProduto, $endPoint['produto']));
                
                    if (empty($dadosProduto))
                    {
                        include_once ("/_pages/404.php");
                    }
                    else
                    {
                        include_once ("/_pages/produto.php");
                    }
                }
                break;            

            case "marcas" :
                include_once ("/_pages/marcasLista.php");
                break;                        
            
            case "recursos" :
                include_once ("/_pages/recursos.php");
                break;                
            
            case "sobreahooray" :
                include_once ("/_pages/institucional.php");
                break;                            

            case "minhaconta" :
                include_once ("/_pages/minhaConta.php");
                break;

            case "login" :
                if (!empty($paginas[2]) && strtolower($paginas[2]) == "recuperarsenha")
                {
                    include_once ("/_pages/recuperarSenha.php");
                }
                else
                {
                    include_once ("/_pages/404.php");
                }
                break;            
            
            case "carrinho" :
                include_once ("_pages/carrinho.php");
                break;

            case "checkout" :
                if ($dadosLogin['ID'] > 0)
                {
                    include_once ("_pages/checkout.php");
                }
                else
                {
                    include_once ("_pages/checkoutLogin.php");
                }
                break;
            
            case "marketplace" :
                include_once ("_pages/marketplace.php");
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
                            //dentro do expert, opção ferramentas:
                            //0 = pagina: Área no próprio site
                            //1 = popup: Link para site externo                            
                            foreach ((array) $rodape['Itens'] as $item)
                            {
                                if ($item['ID'] == "58") //minha conta
                                {
                                ?>    
                                    <li><a href="/minhaconta"><?= $item['Descricao'] ?></a></li>
                                <?php
                                }
                                elseif ($item['ID'] == "41") // contato
                                {
                                ?>
                                    <li><a href="/contato"><?= $item['Descricao'] ?></a></li>
                                <?php
                                }                                
                                elseif ($item['ID'] == "48") // marketplace
                                {
                                ?>
                                    <li><a href="/marketplace"><?= $item['Descricao'] ?></a></li>
                                <?php
                                }
                                else
                                {
                                    if ($item['Ferramentas'] == 2)
                                    {
                                        echo "<li>" . $item['Html'] . "</li>";
                                    }
                                    else 
                                    {
                                        if ($item['Html'] == "#")
                                        {
                                            $abreLink = '';
                                            $fechaLink = '';
                                        }
                                        else
                                        {
                                            $abreLink  = '<a href="';
                                            $abreLink .= ($item['Ferramentas'] == 1) ? $item['Html'] : "/" . str_replace(" ", "", $rodape['Descricao']) . "?secao=" . $item['ID'] . "#" . $item['ID'];
                                            $abreLink .= '"';
                                            $abreLink .= ($item['Ferramentas'] == 1) ? " target=\"_blank\"" : "";
                                            $abreLink .= '>';
                                            $fechaLink = '</a>';
                                        }
                                    ?>
                                        <li><?= $abreLink ?><?= $item['Descricao'] ?><?= $fechaLink ?></li>
                                <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>

            <?php
            $dadosCadastrais = getRest(str_replace("{IDParceiro}","1031", $endPoint['dadoscadastrais']));
            $endCadastral = getRest(str_replace("{IDParceiro}","1031", $endPoint['endcadastral']));
            ?>
            
            <div class="copyright text-center">
                &copy; <?= ("2017") == date("Y") ? date("Y") : "2017-" . date("Y") ?> <?= $dadosCadastrais['RazaoSocial'] ?> | Todos os direitos reservados
                <br>
                <small>
                    <?= $endCadastral['Enderecos'][0]['Logradouro'] . ", " . $endCadastral['Enderecos'][0]['Numero'] 
                    . " - " . $endCadastral['Enderecos'][0]['Cidade']['Nome'] . " - " . $endCadastral['Enderecos'][0]['Cidade']['Estado']['Sigla'] 
                    . " | CEP: " . mascara($endCadastral['Enderecos'][0]['CEP'], "#####-###")
                    . " | CNPJ: " . mascara($dadosCadastrais['CNPJ'],"##.###.###/####-##") 
                    . " | Powered by <img src=\"/images/site/logo-invento-pb.png\" /> "?>
                </small>
            </div>
            <br/>
        </footer>

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
                                <form name="loginForm" id="loginForm" method="post" action="/" onSubmit="return obterBearer()">
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <div class="form-group">
                                                <input type="email" name="loginEmail" id="loginEmail" placeholder="E-mail" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="loginSenha" id="loginSenha" placeholder="Senha" required="required">	
                                            </div>
                                            <div class="form-group">
                                                <a href="#tab-esqueci" aria-controls="tab-cadastre" role="tab" data-toggle="tab">Esqueci a senha</a>
                                                <br>
                                                <span id="resultBearer"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg">ENTRAR</button>
                                </form>
                                <form name="autForm" id="autForm" method="post" action="<?= (!empty($paginas[2]) && strtolower($paginas[2]) == "recuperarsenha") ? "/" : $URISite ?>">
                                    <input type="hidden" name="loginResult" id="loginResult" value="<?= md5("login") ?>">
                                    <input type="hidden" name="addwhislist" id="addwhislist" value="0">
                                </form>
                            
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab-cadastre">
                                <form name="cadForm" id="cadForm" method="post" action="/">
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" name="cadNome" placeholder="Nome" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" name="cadEmail" placeholder="E-mail" required="required">
                                            </div>
                                            <div class="form-group">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="cadastroResult" id="cadastroResult" value="<?= md5("cadastro") ?>">
                                    <button type="submit" class="btn btn-lg">CADASTRAR</button>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab-esqueci">
                                <h4>Esqueci a senha</h4>
                                <form name="recuperarSenhaForm" id="recuperarSenhaForm" method="post" action="/" onsubmit="false">
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <div class="form-group">
                                                <input type="email" name="recEmail" id="recEmail" placeholder="Digite seu e-mail" required="required">
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-md-offset-2 col-sm-12">
                                            <span id="resultRecuperarSenha"></span>
                                        </div>
                                    </div>
                                    <button type="button" onclick="recuperarSenha();" class="btn btn-lg">Enviar</button>
                                    <p class="text-left"><a href="#tab-login" aria-controls="tab-cadastre" role="tab" data-toggle="tab">Voltar</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade modal-hooray" id="modal-carrinho" tabindex="-1" role="dialog" aria-labelledby="modal-carrinho-compras">
            <div class="modal-dialog modal-sm" role="document">

                <div class="modal-content modal-carrinho-content">
                    <div id="carrinhoCompra" class="modal-body">

                        <div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>

                        <?php
                        if (!empty($numCarrinho))
                        {
                            $carrinho = getRest(str_replace("{IDCarrinho}", $numCarrinho, $endPoint['obtercarrinho']));
                        }
                        
                        if (!empty($carrinho) && !empty($carrinho['Itens']))
                        {
                        ?>
                            <div class="nav">
                                <a href="/carrinho">IR PARA O CARRINHO</a>
                            </div>
                        
                            <ul>
                                <?php 
                                foreach ((array) $carrinho['Itens'] as $itemCarrinho)
                                {
                                ?>
                                    <li id="itemCarinhoModal<?= $itemCarrinho['Id'] ?>">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <img src="<?= $itemCarrinho['ProdutoImagemMobile'] ?>" title="<?= $itemCarrinho['ProdutoDescricao'] ?>" />
                                            </div>
                                            <div class="col-xs-7">
                                                <p>
                                                    <?= $itemCarrinho['ProdutoDescricao'] ?><br>
                                                    <?= $itemCarrinho['Marca'] ?><br>
                                                    Quantidade: <?= $itemCarrinho['Quantidade'] ?><br>
                                                    <span><?= formatar_moeda($itemCarrinho['ValorTotal']) ?></span>
                                                    <i id="resultDelCarrinho<?= $itemCarrinho['Id'] ?>"></i>
                                                </p>
                                            </div>
                                            <div class="col-xs-2 text-right">		        				
                                                <!--<a href="#"><span aria-hidden="true">&times;</span></a>-->
                                                <a href="javascript:retirarCarrinhoModal('<?= $itemCarrinho['Id'] ?>');" title="Retirar do carrinho"><img src="/images/site/i-close-red-med.png"></a>&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                            <form method="post" action="/carrinho">
                                <button type="submit" class="btn btn-primary btn-lg">CHECKOUT</button>
                            </form>
                        <?php
                        }
                        else
                        {
                            echo "<div class=\"nav\">CARRINHO</div>";
                            echo "<div>Seu carrinho está vazio.</div>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>       
        
        <?php
        if (!empty($carrinho) && !empty($carrinho['Itens']))
        {
        ?>
            <script type="text/javascript">
                $('#qtdeCarrinho').html('&nbsp;<?= count($carrinho['Itens']) ?>');
            </script>            
        <?php    
        }
        ?>
        <!-- js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/javascripts/bootstrap.min.js"></script>
	<script src="/javascripts/nouislider.js"></script>
	<script src="/javascripts/util.js"></script>
	<script src="/javascripts/jquery.zoom.js"></script>
	<script type="text/javascript">$("#enlarge").zoom();</script>
        
        <?php
        if (!empty($tipoBusca)) // script para filtro de preço nas pagina de busca
        {
        ?>
            <script type="text/javascript">
                var snapSlider = document.getElementById('slider-handles');

                noUiSlider.create(snapSlider, {
                    start: ['<?= $minPreco ?>', '<?= $maxPreco ?>'],
                    connect: true,
                    range: {
                        'min': [<?= $minPreco ?>],
                        'max': [<?= $maxPreco ?>]
                    }
                });

                var snapValues = [
                    document.getElementById('slider-snap-value-lower'),
                    document.getElementById('slider-snap-value-upper')
                ];

                snapSlider.noUiSlider.on('update', function (values, handle) {
                    snapValues[handle].innerHTML = Math.round(values[handle]);
                });
                
                snapSlider.noUiSlider.on('change', function (values, handle) {
                    $('#postvalormin').val(values[0]);
                    $('#postvalormax').val(values[1]);
                    filtrarBusca(-1);
                });                
            </script>
        <?php
        }
        ?>
        
        <?php
        if (in_array($paginas[1], ['recursos', 'sobreahooray'])) // ancoragem das paginas de ajuda
        {
        ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('a[href^="#"]').on('click', function (e) {
                        e.preventDefault();

                        var target = $('.ancora');
                        var $target = $(target);

                        $('html, body').stop().animate({
                            'scrollTop': $target.offset().top
                        }, 900, 'swing', function () {
                            window.location.hash = "";
                        });
                    });
                });
            </script>
        <?php  
        }
        ?>    
    </body>
</html>