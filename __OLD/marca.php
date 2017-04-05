<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$dadosProdMarcas = ["Count" => "16",
                    "UltimoID" => -1,
                    "UltimoPreco" => -1,
                    "UltimaDescricao" => "",
                    "TipoOrdenacao" => 0,
                    "ProdutoID" => -1,
                    "SecaoID" => -1,
                    "MarcaID" => $IDMarca,
                    "ProdutoCategoriaID" => -1,
                    "ProdutoSubCategoriaID" => -1
    ];

$produtosMarca = sendRest($endPoint['busca'], $dadosProdMarcas, "POST");
?>

<section class="vitrine-foto-principal">
    <div class="container">
        <img src="<?= $detalheMarca['Imagem'] ?>" class="img-responsive" title="<?= $detalheMarca['Descricao'] ?>"/>
    </div>
    <div class="site-breadcrumb">
        <a href="javascript:window.history.back();"><span class="glyphicon glyphicon-menu-left"></span>Voltar</a> 
    </div>
</section>	

<section class="vitrine-submenu">
    <ul class="nav">
        <li><a href="#">Mais vendidos</a></li>
        <li><a href="#">Lançamentos</a></li>
        <li><a href="#">Ver tudo</a></li>
    </ul>
</section>

<section class="vitrine-marca cf">
    <img src="<?= $detalheMarca['Logo'] ?>" />
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
                            <div class="handles-wrap">
                                <div id="slider-handles"></div>
                                <div id="slider-snap-value-lower"></div>
                                <div id="slider-snap-value-until">até</div>
                                <div id="slider-snap-value-upper"></div>
                            </div>
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
            foreach ((array) $produtosMarca['ProdutoBuscaItens'] as $produto)
            {
            ?>
                <li>
                    <a href="/produto?id=<?= $produto['ID'] ?>">
                        <div class="list-wrapper">
                            <img class="img-responsive" src="<?= $produto['Imagem'] ?>" />
                            <div class="vitrine-produtos-titulo"><?= $produto['Descricao'] ?></div>
                            <div class="vitrine-produtos-marca"><?= $produto['Marca'] ?></div>
                            <div class="vitrine-produtos-preco"><b><?= formatar_moeda($produto['PrecoVigente']) ?></b><?= (!empty($produto['PrecoDe'])) ? " | <s>" . formatar_moeda($produto['PrecoDe']). "</s>" : "" ?></div>
                            <div class="vitrine-produtos-tamanho"><?= $produto['Descritor'] ?></div>
                        </div>
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>

        <div class="vitrine-ver-tudo">
            <a href="">Ver tudo <?= $detalheMarca['Descricao'] ?></a>
        </div>
    </div>
</section>

<section class="vitrine-sobre">
<div>
    <h4>Sobre <?= $detalheMarca['Descricao'] ?></h4>
    <p>
        <?= substr($detalheMarca['DescricaoDetalhada'], 0, 200) ?>
    </p>
    <?php
    if (strlen($detalheMarca['DescricaoDetalhada']) > 200)
    {
    ?>
        <p class="text-center"><a data-toggle="collapse" aria-expanded="false" data-target="#vitrine-sobre-collapse">Mostrar mais</a></p>
        <p class="collapse" id="vitrine-sobre-collapse">
            <?= substr($detalheMarca['DescricaoDetalhada'], 200) ?>
        </p>
    <?php
    }
    ?>
</div>
</section>