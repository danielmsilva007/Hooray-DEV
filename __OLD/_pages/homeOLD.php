<?php

if (!defined('HoorayWeb')) 
{
    die;
}

?>

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
        $marcasSite = getRest($endPoint['marca']);
        foreach ((array) $marcasSite as $marca)
        {
        ?>
            <li><a href="/marcas/<?= $marca['ID'] ?>"><img src="<?= $marca['UrlImagem'] ?>" /></a></li>
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
        ?>
            <div class="<?= $larguraVitrine[$contVitrine] ?>">
                <div class="col-item" style="background-image: url(<?= $vitrine['UrlImagem'] ?>)">
                    <div class="mosaico-tarja">
                        <?= $vitrine['Titulo'] ?> <br />
                        <?= $vitrine['Descricao'] ?>
                    </div>
                </div>
            </div>
        <?php
            $contVitrine ++;
        }
        ?>
    </div>
</section>
 
<section class="mais-vendidos">
    <h3>Mais vendidos</h3>
    <div id="mais-vendidos-carousel" class="container-carousel carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="row">
                    
                    <div class="col-md-3 p-thumb">
                        <div class="p-label-sale">
                            <img src="./images/mais-bike.png" />
                            <span>Jaqueta Kuza Unisex</span>
                            <span>Patagonia</span>
                            <span>R$ 498,30</span>
                            <style type="text/css">
                                .row div.p-thumb .p-label-sale::before{
                                    content: "SALE" !important;
                                    position: absolute;
                                    top: 5px;
                                    color: #ff6666;
                                    font-weight: lighter;
                                    font-family: 'PT Sans Narrow', sans-serif;
                                }
                            </style>
                        </div>
                    </div>                
                    
                    
                <?php
                $contMV = 0;
                $maisVendidosSite = getRest($endPoint['maisvedidos']);
                foreach ((array) $maisVendidosSite[0]['Itens'] as $maisvendidos)
                {
                    if (($contMV > 0) && ($contMV <> count($maisVendidosSite[0]['Itens'])) && (($contMV % 4) == 0))
                    {
                        echo "</div></div><div class=\"item\"><div class=\"row\">";
                    }
                    
                    if ($maisvendidos['Produto']['Lancamento'] == TRUE)
                    {
                        $label = "p-label-new";
                    }
                    elseif ($maisvendidos['Produto']['PercentualDesconto'] > 0)
                    {
                        $label = "p-label-off";
                    }
                    else
                    {
                        $label = "p-label";
                    }
                    
                ?>
                    <div class="col-md-3 p-thumb">
                        <div class="<?= $label ?>">
                            <img src="<?= $maisvendidos['Produto']['ImagemMobile'] ?>" />
                            <span><?= $maisvendidos['Produto']['Descricao'] ?></span>
                            <span><?= $maisvendidos['Produto']['Categoria']['Descricao'] ?></span>
                            <span><?= formatar_moeda($maisvendidos['Produto']['PrecoVigente']) ?></span>
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
        <ul>
            <?php
            foreach ((array) $menusSite as $menu) 
            {            
            ?>
                <li>
                    <ul>
                        <li><?= $menu['Descricao'] ?></li>
                        <?php
                        foreach ((array) $menu['Categorias'] as $subtitulo) 
                        {                        
                            echo "<li><a href=\"/categorias/" . $subtitulo['ID'] . "\">" . $subtitulo['Descricao'] . "</a></li>";
                        }
                        ?>
                    </ul>
                </li>            
            <?php    
            }
            ?>
        </ul>
    </div>
</section>

<section class="customize clearfix">
    <div><a href="#">Customize sua prancha</a></div>
</section>

<section class="descubra">
    <h3>Descubra Mais</h3>
    <form class="form-inline" method="post" action="<?= filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING) ?>">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> </button>
            </div>
            <input class="form-control" type="text" name="" placeholder="Equipamentos & Vestuário" />
        </div>
    </form>	
</section>

<section class="saiba-mais">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="atendimento-i"></div> 
                    <p>
                        <span>Precisa de Ajuda?</span>
                        Fale com nossos especialistas
                        e tire suas dúvidas <br />
                        seg. - sexta | 9am - 6pm <br />
                        011 91244 7602
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="newsletter-i"></div>
                    <p>
                        <span>Newsletter</span>
                        Descontos, cupons e novidades
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="wishlist-i"></div>
                    <p>
                        <span>Wishlist</span>
                        Lista de presentes ou simplesmente
                        guardar para depois
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>