<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$phpGet = filter_input_array(INPUT_GET);
if (!empty($phpGet))
{
    $buscaPagina = $phpGet[array_keys($phpGet)[0]];
}

if (!empty($buscaPagina))
{
    $dadosBusca = ["Chave" => $buscaPagina,
                   "Count" => 16,
                   "UltimoID" => -1,
                   "UltimoPreco" => -1,
                   "UltimaDescricao" => "",
                   "TipoOrdenacao" => -1, // Nenhum = -1, Nome = 0, MaiorPreco = 1, MenorPreco = 2, NomeDecrescente = 3
                   "ProdutoID" => -1,
                   "ProdutoCategoriaID" => -1,
                   "ProdutoSubCategoriaID" => -1
    ];

    $resultadoBusca = sendRest($endPoint['busca'], $dadosBusca, "POST");
}

if (empty($buscaPagina))
{
    $mensagemBusca = "Por favor informe um termo para busca.";
}
elseif (!empty($buscaPagina) && $resultadoBusca['TotalRegistros'] > 0)
{
    $mensagemBusca = "Resultados para '" . $buscaPagina . "'.";
}
else 
{
   $mensagemBusca = "Não foram encontrados resultados para <span class=\"string-result\">'" . $buscaPagina . "'</span>.";
}

?>

<script type="text/javascript">
    function filtrar(ordenacao)
    {
        document.getElementById("gridBusca").style.opacity = "0.9";
        
        $.post('/_pages/filtro.php', {posttipofiltro:'<?= md5("buscaCategoria") ?>'},
        function(resultadoFiltro)
        {
            $('#gridBusca').html(resultadoFiltro);
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
                <a href="/">Busca</a> <span class="glyphicon glyphicon-menu-right"></span>
                <span class="page-title"><?= (!empty($buscaPagina) && $resultadoBusca['TotalRegistros'] > 0) ? $resultadoBusca['TotalRegistros'] : "0" ?> resultados para '<?= $buscaPagina ?>'.</span>
            </div>
        </div>
    </div>
</section>

<?php
if (!empty($buscaPagina) && $resultadoBusca['TotalRegistros'] > 0)
{
?>
    <section class="categoria-submenu hidden-xs">
        <ul class="nav">
            <li><a href="javascript:filtrar('-1')">Padrão</a></li>
            <li><a href="javascript:filtrar('0')">Descrição A - Z</a></li>
            <li><a href="javascript:filtrar('3')">Descrição Z - A</a></li>
            <li><a href="javascript:filtrar('1')">Maior preço </a></li>
            <li><a href="javascript:filtrar('2')">Menor preço</a></li>
        </ul>
    </section>
<?php
}
else
{
?>
    <div class="resultado-busca">
        <h3><?= $mensagemBusca ?></h3>
    </div>
<?php
}
?>

<?php
if (!empty($buscaPagina) && $resultadoBusca['TotalRegistros'] > 0)
{
?>
    <section class="vitrine-marca cf">
        <div class="filtro-botao"><a href="#">FILTROS</a></div>
    </section>

    <section class="vitrine-produtos cf">
        <div class="container">    

            <!-- facets -->
            <div class="categoria-menu-esquerdo" id="filtro-conteudo">
                <div class="categoria-menu-esquerdo-filtro">
                    <h4>Filtros</h4>
                    <span><?= (!empty($buscaPagina) && $resultadoBusca['TotalRegistros'] > 0) ? $resultadoBusca['TotalRegistros'] : "0" ?> itens</span>
                    <button type="button" onclick="filtrar()">Limpar Filtros</button>
                </div>
                <div class="panel-group categoria-filtros" id="categoria-filtros">
                    <div class="panel">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#categoria-filtros-tamanho" class="tab-toggle collapsed">
                                    Por tamanho
                                </a>
                            </h4>
                        </div>
                        <div id="categoria-filtros-tamanho" class="panel-collapse collapse">
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
                                <a data-toggle="collapse" href="#categoria-filtros-tipo" class="tab-toggle collapsed">
                                    Por tipo
                                </a>
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
                                <a data-toggle="collapse" href="#categoria-filtros-onda" class="tab-toggle collapsed">
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
                                <a data-toggle="collapse" href="#categoria-filtros-modelo" class="tab-toggle collapsed">
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
                                <a data-toggle="collapse" href="#categoria-filtros-tipo-prancha" class="tab-toggle collapsed">
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
                                <a data-toggle="collapse" href="#categoria-filtros-nivel" class="tab-toggle collapsed">
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
                                <a data-toggle="collapse" href="#categoria-filtros-volume" class="tab-toggle collapsed">
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
                                <a data-toggle="collapse" href="#categoria-filtros-quilha" class="tab-toggle collapsed">
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
                                <a data-toggle="collapse" href="#categoria-filtros-tech" class="tab-toggle collapsed">
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
            <!-- end facets -->            
            
            <div id="gridBusca">
                <ul class="list-inline">
                    <?php
                    foreach ((array) $resultadoBusca['ProdutoBuscaItens'] as $produtoBusca)
                    {
                    ?>
                        <li>
                            <a href="/produto?id=<?= $produtoBusca['ID'] ?>">
                                <div class="list-wrapper">
                                    <img class="img-responsive" src="<?= $produtoBusca['Imagem'] ?>" />
                                    <div class="vitrine-produtos-titulo"><?= $produtoBusca['Descricao'] ?></div>
                                    <div class="vitrine-produtos-marca"><?= $produtoBusca['Marca'] ?></div>
                                    <div class="vitrine-produtos-preco"><b><?= formatar_moeda($produtoBusca['PrecoVigente']) ?></b><?= (!empty($produtoBusca['PrecoDe'])) ? " | <s>" . formatar_moeda($produtoBusca['PrecoDe']). "</s>" : "" ?></div>
                                    <div class="vitrine-produtos-tamanho">VITRINE</div>
                                </div>
                            </a>
                        </li>
                    <?php
                    }
                    ?>                
                </ul>
            </div>
        </div>
    </section>

<?php
}
?>

<section class="descubra">
    <h3>Nova busca</h3>
    <form class="form-inline" name="buscarefinada" method="get" action="/busca">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> </button>
            </div>
            <input class="form-control" type="text" name="termobusca" placeholder="Equipamentos & Vestuário" required="required" />
        </div>
    </form>	
</section>

<script type="text/javascript">

    var snapSlider = document.getElementById('slider-handles');

    noUiSlider.create(snapSlider, {
        start: ['511', '711'],
        connect: true,
        range: {
            'min': [511],
            '600': [600],
            '601': [601],
            '602': [602],
            'max': [711]
        }
    });

    var snapValues = [
        document.getElementById('slider-snap-value-lower'),
        document.getElementById('slider-snap-value-upper')
    ];

    snapSlider.noUiSlider.on('update', function (values, handle) {
        snapValues[handle].innerHTML = Math.round(values[handle]);
    });
</script>