<?php

if (!defined('HoorayWeb')) 
{
    die;
}

$categoriaSite = getRest(str_replace("{IDCategoria}", $IDCategoria, $endPoint['categoria']));
// TODO tratar retorno de categoria nao existente

?>

<section class="categoria-foto-principal">
    <div class="container-fluid" style="background-image: url(<?= $categoriaSite['Imagem'] ?>);">
        <div class="vertical-center categoria-foto-titulo"><?= $categoriaSite['Descricao'] ?></div>
    </div>
</section>

<section class="site-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="/"><span class="glyphicon glyphicon-menu-left"></span>Voltar</a> 
            </div>
            <div class="col-md-10">
                <a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
                <a href="#">Surf</a> <span class="glyphicon glyphicon-menu-right"></span>
                <a href="#">Pranchas</a> <span class="glyphicon glyphicon-menu-right"></span>
                <span class="page-title">Al Merrick</span>
            </div>
        </div>
    </div>
</section>

<section class="categoria-submenu hidden-xs">
    <ul class="nav">
        <li><a href="#">Destaques</a></li>
        <li><a href="#">Mais vistos</a></li>
        <li><a href="#">Lançamentos</a></li>
        <li><a href="#">Maior preço </a></li>
        <li><a href="#">Menor preço</a></li>
        <li><a href="#">% Off</a></li>
    </ul>

</section>

<section class="vitrine-marca cf">
    <div class="filtro-botao"><a href="#">FILTROS</a></div>
</section>

<section class="vitrine-produtos cf">
    <div class="container">

        <div class="categoria-menu-esquerdo" id="filtro-conteudo">

            <div class="categoria-menu-esquerdo-filtro">
                <h4>Filtros</h4>
                <span>52 itens</span>
                <button>Limpar Filtros</button>
            </div>

            <div class="panel-group categoria-filtros" id="categoria-filtros">

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-tamanho" class="tab-toggle">
                                Por tamanho</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-tamanho" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <label>
                                <input type="range" class="range-slider" data-orientation="horizontal">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-tipo" class="tab-toggle">
                                Por tipo</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-tipo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-onda" class="tab-toggle">
                                Por Tamanho de onda</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-onda" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-modelo" class="tab-toggle">
                                Por Modelo de prancha</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-modelo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-tipo-prancha" class="tab-toggle">
                                Por Tipo de prancha</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-tipo-prancha" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-nivel" class="tab-toggle">
                                Por Nível do surfista</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-nivel" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-tamanho" class="tab-toggle">
                                Por Tamanho</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-tamanho" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-volume" class="tab-toggle">
                                Por Volume</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-volume" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-quilha" class="tab-toggle">
                                Por Sistema de quilha</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-quilha" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#categoria-filtros" href="#categoria-filtros-tech" class="tab-toggle">
                                Por Tecnologia</a>
                        </h4>
                    </div>
                    <div id="categoria-filtros-tech" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label><input type="checkbox" name=""> Novas</label>
                            <label><input type="checkbox" name=""> Segunda prancha</label>
                            <label><input type="checkbox" name=""> De fábrica</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <ul class="list-inline">

            <?php
            $produtosSite = getRest(str_replace("{IDCategoria}", $IDCategoria, $endPoint['prodcategoria']));            
            
            foreach ((array) $produtosSite['ProdutoBuscaItens'] as $produto)
            {
            ?>
                <li>
                    <a href="/produtos/<?= $produto['ID'] ?>">
                        <div class="list-wrapper">
                            <img class="img-responsive" src="/images/produtos/vitrine-prancha-azul.png" />
                            <div class="vitrine-produtos-titulo"><?= $produto['Descricao'] ?></div>
                            <div class="vitrine-produtos-marca"><?= $produto['Categoria']['Descricao'] ?></div>
                            <div class="vitrine-produtos-preco"><b><?= formatar_moeda($produto['PrecoVigente']) ?></b> <?= (!empty($produto['PrecoDe'])) ? "| <s>" . formatar_moeda($produto['PrecoDe']) . "</s>" : "" ?></div>
                            <div class="vitrine-produtos-tamanho"><?= $produto['Descritor'] ?> </div>
                        </div>
                    </a>
                </li>
            <?php
            }
            ?>

        </ul>
    </div>
</section>
