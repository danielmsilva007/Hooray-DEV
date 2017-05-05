<?php
if (!defined('HoorayWeb')) 
{
    die;
}
?>

<script>
      function enviarNewsLetter()
      {
        $('#retornoNews').html('Enviando...');
        
        var dataString = 'emailInscricao=' + document.getElementById('newsEmail').value;
            dataString += '&postnews=<?= md5("enviarNewsLetter") ?>';

        $.ajax({
            type: "post",
            url: "/_pages/enviarContato.php",
            data: dataString,
            cache: false,
            success: function (retornoPHP) 
            {
                $('#retornoNews').html(retornoPHP);
            }
        });
        
        document.getElementById('newsEmail').value = '';
    }
</script>
  
<section>
    <div id="carousel-full-home" class="carousel slide carousel-home" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php
            $bannersSite = getRest($endPoint['banner']);
            for ($i = 0; $i < count($bannersSite[0]['BannerItens']); $i++)
            {
            ?>    
                <li data-target="#carousel-full-home" data-slide-to="<?= $i ?>"<?= ($i == 0) ? " class=\"active\"" : "" ?>></li>
            <?php    
            }
            ?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?php
            $i = 0;            
            foreach ((array) $bannersSite[0]['BannerItens'] as $banner)
            {
            ?>
                <div class="item<?= ($i == 0) ? " active" : "" ?>">
                    <a href="<?= $banner['HotSite'] ?>">
                        <div class="item-cover" style="background-image: url(<?= $banner['Imagem'] ?>)"></div>
                    </a>
                    <div class="carousel-caption">
                        <?= htmlentities($banner['Descricao']) ?>
                    </div>
                </div>
            <?php
                $i ++;
            }
            ?>
        </div>

        <!-- Controls -->
    </div>
</section>

<section class="marcas cf">
    <ul>
        <?php
        $marcasDestaque = getRest($endPoint['marcasdestaque']);
        
        function odernacaoMarcas($a, $b)
        {
            return strcmp($a['Descricao'], $b['Descricao']);
        }
        
        if (!empty($marcasDestaque))
        {
            usort($marcasDestaque, "odernacaoMarcas"); // ordenas as marcas
        }
        
        foreach ((array) $marcasDestaque as $marca)
        {
        ?>
            <li><a href="/marca?id=<?= $marca['ID'] ?>"><img src="<?= $marca['Logo'] ?>" /></a></li>
        <?php
        }
        ?>
    </ul>
</section>

<section class="mosaico">
    <div class="row">
        <?php
        $larguraVitrine = array ( // larguras das colunas da vitrine
            1 => 'col-md-4',
            2 => 'col-md-4',
            3 => 'col-md-4',
            4 => 'col-md-8',
            5 => 'col-md-4',
            6 => 'col-md-6',
            7 => 'col-md-6'
        );
        
        $vitrineSite = getRest($endPoint['vitrine']);
        
        $contVitrine = 1;
        foreach ((array) $vitrineSite as $vitrine)
        {
            if (in_array($contVitrine, [4,6])) // gera uma nova linha nos itens 4 e 6
            {
                echo "</div><div class=\"row\">";
            }
            
            $linkVitrine = "#";
                    
            if (!empty($vitrine['Produto']))
            {
                $linkVitrine = "/produto?id=" . $vitrine['Produto']['ID'];
            }
            elseif (!empty($vitrine['BlogArtigo']))
            {
                $linkVitrine = "/blogpost?id=" . $vitrine['BlogArtigo']['ID'];
            }
            elseif (!empty($vitrine['Categoria']))
            {
                $linkVitrine = "/categoria?id=" . $vitrine['Categoria']['ID'];
            }            
            elseif (!empty($vitrine['Marca']))
            {
                $linkVitrine = "/blogpost?id=" . $vitrine['Marca']['ID'];
            }

        ?>
            <div class="<?= $larguraVitrine[$contVitrine] ?>">
                <a href="<?= $linkVitrine ?>">
                    <div class="col-item" style="background-image: url(<?= $vitrine['UrlImagem'] ?>)">
                        <div class="mosaico-tarja">
                            <?= $vitrine['Titulo'] ?> <br />
                            <?= (empty($vitrine['BlogArtigo'])) ? $vitrine['Produto']['Descricao'] : $vitrine['BlogArtigo']['Titulo'] ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php
            $contVitrine ++;
        }
        ?>
    </div>
</section>

<section class="produto-relacionado hidden-xs">	  	
    <h3>Destaques</h3>
    <div id="mais-vendidos-carousel" class="container-carousel carousel slide" data-ride="carousel">

        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="row">
                    <?php
                    $contMV = 0;
                    $maisVendidosSite = getRest($endPoint['maisvedidos']);
                    foreach ((array) $maisVendidosSite[0]['Itens'] as $maisvendidos)
                    {
                        if (($contMV > 0) && ($contMV <> count($maisVendidosSite[0]['Itens'])) && (($contMV % 4) == 0))
                        {
                            echo "</div></div><div class=\"item\"><div class=\"row\">";
                        }
                        
                        if (!empty($maisvendidos['Produto']['PercentualDesconto']) && $maisvendidos['Produto']['PercentualDesconto'] > 0)
                        {
                            $label = "p-label-off" . $contMV;
                            $content = floor($maisvendidos['Produto']['PercentualDesconto']) . "% OFF";
                            $color = "#ff6666";
                        }
                        elseif (!empty($maisvendidos['Produto']['Lancamento']))
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
                                </a>
                                <a href="/produto?id=<?= $maisvendidos['Produto']['ID'] ?>" title="<?= (strlen($maisvendidos['Produto']['Descricao']) > 35) ? $maisvendidos['Produto']['Descricao'] : "" ?>">
                                    <span><?= (strlen($maisvendidos['Produto']['Descricao']) > 35) ? substr($maisvendidos['Produto']['Descricao'],0,32) . "..." : $maisvendidos['Produto']['Descricao'] ?></span>
                                </a>
                                <a href="/produto?id=<?= $maisvendidos['Produto']['ID'] ?>">
                                    <span><?= $maisvendidos['Produto']['Marca']['Descricao'] ?></span>
                                    <span><?= (!empty($maisvendidos['Produto']['PrecoDePor'])) ? "<s>" . formatar_moeda($maisvendidos['Produto']['PrecoDePor']['PrecoDe']) . "</s> | " : "" ?><?= formatar_moeda($maisvendidos['Produto']['PrecoVigente']) ?></span>
                                    <span><?= (!empty($maisvendidos['Produto']['SubCategoria']['Descricao'])) ? $maisvendidos['Produto']['SubCategoria']['Descricao'] : "&nbsp;" ?></span>
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

<section class="categoria clearfix">
    <h3>Compre por categorias</h3>
    <div class="container-cat container">
        <?php
        foreach ((array) $menuSite as $secoes) 
        {            
        ?>            
            <div class="cat-col-4">
                <div class="cat-col-col">
                    <div><a href="#"><?= $secoes['Descricao'] ?></a></div>
                    <?php
                    foreach ((array) $secoes['Categorias'] as $subtitulo) 
                    {                        
                        echo "<div><a href=\"/categoria?id=" . $subtitulo['ID'] . "\">" . $subtitulo['Descricao'] . "</a></div>";
                    }
                    ?>                        
                </div>
            </div>    
        <?php
        }
        ?>
    </div>
</section>

<!--
<section class="customize clearfix">
    <div><a href="">Customize sua prancha</a></div>
</section>
-->

<section class="descubra">
    <h3>Descubra Mais</h3>
    <form class="form-inline" name="descrubamais" method="get" action="/busca">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> </button>
            </div>
            <input class="form-control" type="text" name="termobusca" placeholder="o que você procura ?" required="required" />
        </div>
    </form>	
</section>


<?php
$dadosEmpresa = getRest(str_replace("{IDParceiro}","1031", $endPoint['dadoscadastrais']));
?>

<section class="saiba-mais">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="row">
                <div class="col-md-10">
                    <a href="/recursos"><div class="atendimento-i"></div></a>
                    <p><span>Precisa de Ajuda?</span>
                        Fale com nossos especialistas e tire suas dúvidas.<br />
                        segunda - sexta | 9h - 18h <br />
                        <?= mascara(substr($dadosEmpresa['DDDTelefone'],-2) . $dadosEmpresa['Telefone'], "(##) ####-####") ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="newsletter-i"></div>
                    <p><span>Newsletter</span>
                        Se inscreva para receber informações especiais de descontos, cupons e novidades.
                    </p>
                    <div class="input-group newsletter">
                        <div class="input-group-btn">
                            <button type="button" class="btn" onclick="enviarNewsLetter();"><i class="glyphicon glyphicon-menu-right"></i> </button>
                        </div>
                        <input class="form-control" type="text" id="newsEmail" name="newsEmail" placeholder="Digite seu e-mail" />
                    </div>
                    <span id="retornoNews"></span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="row">

                <div class="col-md-10">
                    <a href="/minhaconta"><div class="wishlist-i"></div></a>
                    <p><span>Wishlist</span>
                        Veja a lista com os seus produtos favoritos.
                    </p>
                </div>
            </div>
        </div>

</section>