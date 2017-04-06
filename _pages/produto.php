<?php
if (!defined('HoorayWeb')) 
{
    die;
}

if (!empty($dadosLogin['CarrinhoId']))
{
    $IDCarrinho = $dadosLogin['CarrinhoId'];
}
elseif (!empty($_SESSION['carrinho']))
{
    $IDCarrinho = $_SESSION['carrinho'];
}
else
{
    $IDCarrinho = "-1";
}
?>

<script type="text/javascript">
    function atualizarCarrinho()
    {
        $('#resultCarrinho').html('Adicionando ao seu carrinho...');
        
        var qdtProduto = $('#qdtProduto').val();
        var TipoDescritor = <?= (!empty($dadosProduto['TipoDescritor'])) ? "$('#TipoDescritor').val();" : "'-1';" ?>
        var TipoDescritor2 = <?= (!empty($dadosProduto['TipoDescritor2'])) ? "$('#TipoDescritor2').val();" : "'-1';" ?>
        var TipoDescritor3 = <?= (!empty($dadosProduto['TipoDescritor3'])) ? "$('#TipoDescritor3').val();" : "'-1';" ?>
        var TipoDescritor4 = <?= (!empty($dadosProduto['TipoDescritor4'])) ? "$('#TipoDescritor4').val();" : "'-1';" ?>
        var TipoDescritor5 = <?= (!empty($dadosProduto['TipoDescritor5'])) ? "$('#TipoDescritor5').val();" : "'-1';" ?>
                
        $.post('/_pages/carrinhoEditar.php', {postidlogin:'<?= (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) ? $dadosLogin['ID'] : "-1" ?>',
                                              postidcarrinho:'<?= $IDCarrinho ?>',
                                              postidproduto:'<?= $dadosProduto['ID'] ?>',
                                              postqdteproduto:qdtProduto,
                                              postdescritor:TipoDescritor,
                                              postdescritor2:TipoDescritor2,
                                              postdescritor3:TipoDescritor3,
                                              postdescritor4:TipoDescritor4,
                                              postdescritor5:TipoDescritor5,
                                              postcarrinho:'<?= md5("addCarrinho") ?>'},
        function(dataCarrinho)
        {
            if (dataCarrinho.substring(0,2) == "!!")
            {
                $('#resultCarrinho').html(dataCarrinho.substring(2));
            }
            else
            {
                $('#resultCarrinho').html('Produto adicionado ao seu carrinho.');
                $('#carrinhoCompra').html(dataCarrinho);
            }
        });
        
        $('#qtdeCarrinho').html('');
    }
    
    function addWishList()
    {
        $.post('/_pages/atualizarCadastro.php', {postidlogin:'<?= (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) ? $dadosLogin['ID'] : "-1" ?>',
                                                 postidproduto:'<?= $dadosProduto['ID'] ?>',
                                                 postwishlist:'<?= md5("adicionar") ?>'},
        function(dataWishList)
        {
            $('#retornoWishlist').html(dataWishList);
        });
    }    
</script>

<section class="site-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="javascript:window.history.back();"><span class="glyphicon glyphicon-menu-left"></span>Voltar</a> 
            </div>
            <div class="col-md-10">
                <a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
                <a href="/categoria?id=<?= $dadosProduto['Categoria']['ID'] ?>"><?= $dadosProduto['Categoria']['Descricao'] ?></a> <span class="glyphicon glyphicon-menu-right"></span>
                <a href="/marca?id=<?= $dadosProduto['Marca']['ID'] ?>"><?= $dadosProduto['Marca']['Descricao'] ?></a> <span class="glyphicon glyphicon-menu-right"></span>
                <span class="page-title"><?= $dadosProduto['Descricao'] ?></span>
            </div>
        </div>
    </div>
</section>

<section class="produto">
    <div class="container">
        <div class="row">

            <!-- GALERIA INICIO -->
            <div class="col-md-6">
                <div class="produto-galeria-imagem zoom" id="enlarge">
                    <img src="<?= $dadosProduto['ImagemPlus1'] ?>" />
                </div>

                <div class="row produto-galeria-thumb">
                    <div class="col-xs-3 thumbnail current"><img class="" src="<?= $dadosProduto['ImagemPlus1'] ?>" /></div>
                    <?= (!empty($dadosProduto['ImagemPlus2'])) ? "<div class=\"col-xs-3 thumbnail\"><img class=\"\" src=\"" . $dadosProduto['ImagemPlus2'] ."\" /></div>" : "<div class=\"col-xs-3 thumbnail\"></div>" ?>
                    <?= (!empty($dadosProduto['ImagemPlus3'])) ? "<div class=\"col-xs-3 thumbnail\"><img class=\"\" src=\"" . $dadosProduto['ImagemPlus3'] ."\" /></div>" : "<div class=\"col-xs-3 thumbnail\"></div>" ?>
                    <?= (!empty($dadosProduto['ImagemPlus4'])) ? "<div class=\"col-xs-3 thumbnail\"><img class=\"\" src=\"" . $dadosProduto['ImagemPlus4'] ."\" /></div>" : "<div class=\"col-xs-3 thumbnail\"></div>" ?>
                </div> 
            </div>
            <!-- GALERIA FIM //-->

            <div class="col-md-6">
                <div class="produto-descricao">
                    <form name="dadosProduto" id="dadosProduto" method="post" onsubmit="false">
                    <h4><?= $dadosProduto['Descricao'] ?></h4>
                    <?php
                    if (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0)
                    {
                    ?>
                        <h6><a href="javascript:addWishList();" class="wish"><i class="fa fa-heart-o"></i> <span id="retornoWishlist">Adicionar à minha Wishlist</span></a></h6>
                    <?php
                    }
                    ?>
                    <p>Marca: <span><?= $dadosProduto['Marca']['Descricao'] ?></span></p>
                    <p>Vendido e entregue por: <span><?= $dadosProduto['Fornecedor'] ?></span></p>

                    <div class="row">
                        <div class="produto-descricao-preco">
                            <?= (!empty($dadosProduto['PrecoDePor'])) ? "<s>" . formatar_moeda($dadosProduto['PrecoDePor']['PrecoDe']) . "</s><br>" : "" ?>
                            <?= formatar_moeda($dadosProduto['PrecoVigente']) ?>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <label>Quantidade</label>
                                <input type="number" name="qdtProduto" id="qdtProduto" value="1" class="form-control" min="1">
                            </div>
                        </div>
                        
                        <?php
                        if (!empty($dadosProduto['TipoDescritor']))
                        {
                        ?>                        
                            <div class="form-inline">
                                <div class="form-group">
                                    <label><?= $dadosProduto['TipoDescritor']['Descricao'] ?></label></br>
                                    <select name="TipoDescritor" id="TipoDescritor" class="form-control" min="0" class="input-lg">
                                        <?php
                                        $opcoesDescritor = [];
                                        foreach ((array) $dadosProduto['Skus'] as $sku)
                                        {
                                            if (!in_array($sku['Descritor'], $opcoesDescritor)) // não exibe descritores repetidos
                                            {
                                                array_push($opcoesDescritor, $sku['Descritor']);
                                                echo "<option value=\"" . $sku['Descritor'] . "\">" . $sku['Descritor'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        ?>                        
                           <?php
                        if (!empty($dadosProduto['TipoDescritor2']))
                        {
                        ?>                        
                            <div class="form-inline">
                                <div class="form-group">
                                    <label><?= $dadosProduto['TipoDescritor2']['Descricao'] ?></label></br>
                                    <select name="TipoDescritor2" id="TipoDescritor2" class="form-control" min="0" class="input-lg">
                                        <?php
                                        $opcoesDescritor2 = [];
                                        foreach ((array) $dadosProduto['Skus'] as $sku)
                                        {
                                            if (!in_array($sku['Descritor2'], $opcoesDescritor2))
                                            {
                                                array_push($opcoesDescritor2, $sku['Descritor2']);
                                                echo "<option value=\"" . $sku['Descritor2'] . "\">" . $sku['Descritor2'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        ?>   
                        <?php
                        if (!empty($dadosProduto['TipoDescritor3']))
                        {
                        ?>                        
                            <div class="form-inline">
                                <div class="form-group">
                                    <label><?= $dadosProduto['TipoDescritor3']['Descricao'] ?></label></br>
                                    <select name="TipoDescritor3" id="TipoDescritor3" class="form-control" min="0" class="input-lg">
                                        <?php
                                        $opcoesDescritor3 = [];
                                        foreach ((array) $dadosProduto['Skus'] as $sku)
                                        {
                                            if (!in_array($sku['Descritor3'], $opcoesDescritor3))
                                            {
                                                array_push($opcoesDescritor3, $sku['Descritor3']);
                                                echo "<option value=\"" . $sku['Descritor3'] . "\">" . $sku['Descritor3'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        ?>   
                        <?php
                        if (!empty($dadosProduto['TipoDescritor4']))
                        {
                        ?>                        
                            <div class="form-inline">
                                <div class="form-group">
                                    <label><?= $dadosProduto['TipoDescritor4']['Descricao'] ?></label></br>
                                    <select name="TipoDescritor4" id="TipoDescritor4" class="form-control" min="0" class="input-lg">
                                        <?php
                                        $opcoesDescritor4 = [];
                                        foreach ((array) $dadosProduto['Skus'] as $sku)
                                        {
                                            if (!in_array($sku['Descritor4'], $opcoesDescritor4))
                                            {
                                                array_push($opcoesDescritor4, $sku['Descritor4']);
                                                echo "<option value=\"" . $sku['Descritor4'] . "\">" . $sku['Descritor4'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        ?>   
                        <?php
                        if (!empty($dadosProduto['TipoDescritor5']))
                        {
                        ?>                        
                            <div class="form-inline">
                                <div class="form-group">
                                    <label><?= $dadosProduto['TipoDescritor5']['Descricao'] ?></label></br>
                                    <select name="TipoDescritor5" id="TipoDescritor5" class="form-control" min="0" class="input-lg">
                                        <?php
                                        $opcoesDescritor5 = [];
                                        foreach ((array) $dadosProduto['Skus'] as $sku)
                                        {
                                            if (!in_array($sku['Descritor4'], $opcoesDescritor))
                                            {
                                                array_push($opcoesDescritor5, $sku['Descritor5']);
                                                echo "<option value=\"" . $sku['Descritor5'] . "\">" . $sku['Descritor5'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        ?>   
                    
                    <h5 id="resultCarrinho"></h5>
                    
                    <div class="row">
                        <button type="button" onclick="atualizarCarrinho()">Adicionar ao carrinho</button>
                    </div>

                    <?php
                    $parcelamento = getRest(str_replace(['{IDProduto}', '{valorProduto}'], [$dadosProduto['ID'], $dadosProduto['PrecoVigente']], $endPoint['parcelamento']));
                    ?>
                        
                    <div class="produto-descricao-parcela">
                        <div class="row">
                            <?php
                            if (!empty($parcelamento[0]) && $parcelamento[0]['Numero'] === 0)
                            {
                                $parcBoleto = $parcelamento[0];
                            ?>    
                                <div class="col-md-12">
                                    <ul>
                                        <li><span>À vista no boleto, desconto de <?= floor(100 - ($parcBoleto['Valor'] / $dadosProduto['PrecoVigente'] * 100)) ?>%:</span> <?= formatar_moeda($parcBoleto['Valor']) ?></li>
                                    </ul>
                                </div>
                                <br>
                            <?php
                            }
                            ?>
                            <div class="col-md-12">
                                <span>Pagamento no cartão de crédito:</span>
                            </div>
                             <div class="col-md-6">
                                <ul>                                
                                    <?php
                                    $contParc = 1;
                                    foreach ((array) $parcelamento as $parcela)
                                    {
                                        if ($parcela['Numero'] === 0) continue; //pula parcela do boleto, ja exibida acima
                                        
                                        if ($contParc == ceil((count($parcelamento)) / 2) && count($parcelamento) > 2) //divide em duas colunas
                                        {
                                            echo "</ul></div><div class=\"col-md-6\"><ul>";
                                        }
                                        
                                        echo "<li><span>" . $parcela['Numero'] . "x sem juros de</span> " . formatar_moeda($parcela['Valor']) . "</li>";
                                    
                                        $contParc++;
                                    }
                                    ?>
                                </ul>
                            </div>
                        
                        </div>
                    </div>
                    <div class="produto-descricao-dimensoes"></div>
                    
                    <div class="produto-descricao-atributo">
                        <ul>
                            <?php
                            $infoGenerica = "";
                            foreach ((array) $dadosProduto['Caracteristicas'] as $caracteristica)
                            {
                                if ($caracteristica['Descricao'] != "Vitrine" && !strpos($caracteristica['Descricao'], "GENÉRICA"))
                                {
                                    echo "<li>" . $caracteristica['Descricao'] . ": <span>" . $caracteristica['Valor'] . "</span></li>";
                                }
                                
                                if (strpos($caracteristica['Descricao'], "GENÉRICA") > 0 || strpos($caracteristica['Descricao'], "GENERICA") > 0)
                                {
                                    $infoGenerica .= " " . $caracteristica['Valor'];
                                }
                            }
                            echo "<li><span>" . $infoGenerica . "</span></li>";
                            ?>
                        </ul>
                    </div>
                    
                    <div class="produto-social">
                        COMPARTILHE: <br>
                        <a href=""><i class="produto-social-icon icon-facebook">&#xe800;</i></a>
                        <a href="https://twitter.com/intent/tweet?text=Gostei+de+um+produto+da+Hooray&hashtags=Hooray"><i class="produto-social-icon icon-instagram">&#xe803;</i></a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="produto-mais-info hidden-xs">
    <div class="container">
        <h4>Mais Informações</h4>
        <div class="produto-mais-info-scroll">
            <?= $dadosProduto['DescricaoDetalhada'] ?>
        </div>
    </div>
</section>

<?php
$prodRelacionados = getRest(str_replace("{IDProduto}", $IDProduto, $endPoint['relacionados']));
$tituloRelacionados = "Produtos relacionados";

if (empty($prodRelacionados))
{
    $prodRelacionados = getRest($endPoint['maisvedidos']);
    $tituloRelacionados = "Destaques";
}

if (!empty($prodRelacionados))
{
?>
    <section class="produto-relacionado hidden-xs">	  	
    <h3><?= $tituloRelacionados ?></h3>
    <div id="mais-vendidos-carousel" class="container-carousel carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="row">
                    <?php
                    $contMV = 0;
                    foreach ((array) $prodRelacionados[0]['Itens'] as $maisvendidos)
                    {
                        if (($contMV > 0) && ($contMV <> count($prodRelacionados[0]['Itens'])) && (($contMV % 4) == 0))
                        {
                            echo "</div></div><div class=\"item\"><div class=\"row\">";
                        }
                        
                        if ($maisvendidos['Produto']['PercentualDesconto'] > 0)
                        {
                            $label = "p-label-off" . $contMV;
                            $content = round($maisvendidos['Produto']['PercentualDesconto']) . "% OFF";
                            $color = "#ff6666";
                        }
                        elseif ($maisvendidos['Produto']['Lancamento'] == TRUE)
                        {
                            $label = "p-label-new" . $contMV;
                            $content = "NEW";
                            $color = "#66b3ff";
                        }
                        else
                        {
                            $label = "p-label" . $contMV;
                            $content = "";
                            $color = "";
                        }

                    ?>
                        <div class="col-md-3 p-thumb">
                            <div class="<?= $label ?>">
                                <a href="/produto?id=<?= $maisvendidos['Produto']['ID'] ?>">
                                <img src="<?= $maisvendidos['Produto']['Imagem'] ?>" />
                                <span><?= $maisvendidos['Produto']['Descricao'] ?></span>
                                <span><?= $maisvendidos['Produto']['Marca']['Descricao'] ?></span>
                                <span><?= (!empty($maisvendidos['Produto']['PrecoDePor'])) ? "<s>" . formatar_moeda($maisvendidos['Produto']['PrecoDePor']['PrecoDe']) . "</s> | " : "" ?><?= formatar_moeda($maisvendidos['Produto']['PrecoVigente']) ?></span>
                                </a>
                                <style type="text/css">
                                    .row div.p-thumb .<?= $label ?>::before{
                                        content: "<?= $content ?>" !important;
                                        position: absolute;
                                        top: 5px;
                                        color: <?= $color ?>;
                                        font-weight: lighter;
                                        font-family: 'PT Sans Narrow', sans-serif;
                                    }
                                </style>                            
                            </div>
                        </div>
                    <?php
                        $contMV++;
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="mais-vendidos-carousel-controle">
            <a class="pull-left" href="#mais-vendidos-carousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
            </a>
            <a class="pull-right" href="#mais-vendidos-carousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</section>	
<?php
}
?>