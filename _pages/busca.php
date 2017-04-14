<?php
if (!defined('HoorayWeb')) 
{
    die;
}

if (empty($tipoBusca))
{
    include_once ("/_pages/404.php");
    die;
}

if ($tipoBusca == "busca" && empty($termoBusca))
{
?>    
    <div class="resultado-busca">
        <h3>Por favor informe um termo para a busca.</h3>
    </div>
<?php
}
else
{
    $esperaResultado = '<ul class="list-inline" id="itensGrid"><li><span class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></span></li></ul>';
    
    switch ($tipoBusca)
    {
        case "busca": 
            $postBusca = $termoBusca; 
            break;
        
        case "secao":
            $postBusca = $IDSecao; 
            break;

        case "marca":
            $postBusca = $IDMarca; 
            break;

        case "categoria":
            $postBusca = $IDCategoria; 
            break;        
    }
    
    $infoBreadcrumb = ["busca" => ["URL" => "/busca", 
                                   "Descricao" => "Busca"],
                       "secao" => ["URL" => "/", 
                                   "Descricao" => "secao"],
                       "categoria" => ["URL" => "/", 
                                       "Descricao" => (!empty($categoriaSite['Descricao'])) ? $categoriaSite['Descricao'] : ""],
                       "marca" => ["URL" => "/marcas", 
                                   "Descricao" => (!empty($detalheMarca['Descricao'])) ? $detalheMarca['Descricao'] : ""]
        ];
    
    $dadosBuscaCat = ["Chave" => ($tipoBusca == "busca") ? $termoBusca : "",
                    "Count" => "-1",
                    "UltimoID" => -1,
                    "UltimoPreco" => -1,
                    "UltimaDescricao" => "",
                    "TipoOrdenacao" => 0,
                    "ProdutoID" => -1,
                    "SecaoID" => ($tipoBusca == "secao") ? $IDSecao : -1,
                    "MarcaID" => ($tipoBusca == "marca") ? $IDMarca : -1,
                    "ProdutoCategoriaID" => ($tipoBusca == "categoria") ? $IDCategoria : -1,
                    "ProdutoSubCategoriaID" => -1
        ];

    $resultadoBuscaCat = sendRest($endPoint['buscaestendida'], $dadosBuscaCat, "POST");
    
    $filtros = [];
    $precos = [];
    
    foreach ((array) $resultadoBuscaCat as $produtoBuscaCat)
    {
        foreach ((array) $produtoBuscaCat['Caracteristicas'] as $caracteristica)
        {
            if (!$caracteristica['Filtro']) continue; // só adiciona filtros marcados com TRUE
            
            if (empty($caracteristica['Posicao']))
            {
                $caracteristica['Posicao'] = 999999999; // não definida posição
            }
            
            if (!array_key_exists($caracteristica['TipoID'], $filtros)) //cria a chave com o tipo, se nao existir
            {
                $filtros[$caracteristica['TipoID']] = ["TipoID" => $caracteristica['TipoID'],
                                                       "Descricao" => $caracteristica['Descricao'],
                                                       "Posicao" => $caracteristica['Posicao'],
                                                       "Opcoes" => []
                    ];
            }
            
            if (!array_key_exists($caracteristica['ValorID'], $filtros[$caracteristica['TipoID']]['Opcoes'])) // cria o valor, se nao houver
            {
                $opcaoFiltro = ["ValorID" => $caracteristica['ValorID'],
                                "Valor" => $caracteristica['Valor']
                    ];
                
                $filtros[$caracteristica['TipoID']]['Opcoes'][$caracteristica['ValorID']] = $opcaoFiltro;
            }
        }
        array_push($precos, $produtoBuscaCat['PrecoVigente']);
    }
    
    $minPreco = floor(min($precos));
    $maxPreco = ceil(max($precos));
?>

<?php
    if ($tipoBusca == "categoria")
    {
?>
        <section class="categoria-foto-principal">
            <div class="container-fluid" style="background-image: url(<?= $categoriaSite['Imagem'] ?>);">
                <div class="vertical-center categoria-foto-titulo"><?= $categoriaSite['Descricao'] ?></div>
            </div>
        </section>    
<?php
    }
    
    if ($tipoBusca == "marca")
    {
?>
        <section class="vitrine-foto-principal">
            <div class="container">
                <img src="<?= $detalheMarca['Imagem'] ?>" class="img-responsive" title="<?= $detalheMarca['Descricao'] ?>"/>
            </div>
        </section>
<?php
    }
?>
    <section class="site-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <a href="javascript:window.history.back();"><span class="glyphicon glyphicon-menu-left"></span>Voltar</a> 
                </div>
                <div class="col-md-10">
                    <a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
                    <?= $infoBreadcrumb[$tipoBusca]['Descricao'] ?> <span class="glyphicon glyphicon-menu-right"></span>
                    <span class="page-title" id="countBusca"><?= (!empty($resultadoBuscaCat)) ? count($resultadoBuscaCat) : "0" ?></span><span class="page-title"> produtos encontrados</span>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function limparFiltro()
        {
            $('#gridBusca').html('<?= $esperaResultado ?>');
    
            document.getElementById('filtrosBusca').reset();
    
            $.post('/_pages/filtro.php', {posttipobusca:'<?= $tipoBusca ?>',
                                          posttermobusca:'<?= $postBusca ?>',
                                          posttipofiltro:'<?= md5("buscaProduto") ?>'},
            function(resultadoBusca)
            {
                $('#gridBusca').html(resultadoBusca);
                
                var item = ' item';
                if ($(resultadoBusca).children().length > 1)
                {
                    item = ' itens';
                }
                
                $('#countResult').html($(resultadoBusca).children().length + item);                
            });
        }
    
        function filtrarBusca(ordenacao)
        {
            if (ordenacao >= 0)
            {
                document.filtrosBusca.postordenacao.value = ordenacao;
                
                //document.getElementById('ordem0').style.fontWeight = 'normal';
                document.getElementById('ordem1').style.fontWeight = 'normal';
                document.getElementById('ordem2').style.fontWeight = 'normal';
                //document.getElementById('ordem3').style.fontWeight = 'normal';
                document.getElementById('ordem4').style.fontWeight = 'normal';
                document.getElementById('ordem5').style.fontWeight = 'normal';
                
                document.getElementById('ordem' + ordenacao).style.fontWeight = 'bold';
            }
        
            $('#gridBusca').html('<?= $esperaResultado ?>');
            
            $('#countResult').html('Filtrando...');
        
            $.post('/_pages/filtro.php', $("#filtrosBusca").serialize(),
            function(resultadoBusca)
            {
                $('#gridBusca').html(resultadoBusca);
                
                var item = ' item';
                if ($(resultadoBusca).children().length > 1)
                {
                    item = ' itens';
                }
                
                $('#countResult').html($(resultadoBusca).children().length + item);
            });
            
            
        }
        
        function valorSlider()
        {
            var valores = snapSlider.noUiSlider.get();
            alert('inicial: ' + valores[0] + ' - Final: ' + valores[1]);
        }
    </script>

    <section class="categoria-submenu hidden-xs">
        <ul class="nav">
            <!--
            <li>
                <a href="javascript:filtrarBusca(0);" id="ordem0">Descrição A-Z</a>
            </li>
            <li>
                <a href="javascript:filtrarBusca(3);" id="ordem3">Descrição Z-A</a>
            </li>
            -->
            <li>
                <a href="javascript:filtrarBusca(4);" id="ordem4">Lançamento</a>
            </li>
            <li>
                <a href="javascript:filtrarBusca(2);" id="ordem2">Menor preço</a>
            </li>
            <li>
                <a href="javascript:filtrarBusca(1);" id="ordem1">Maior preço</a>
            </li>
            <li>
                <a href="javascript:filtrarBusca(5);" id="ordem5">% OFF</a>
            </li>            
        </ul>
    </section>
    
    <?php
    if (!empty($resultadoBuscaCat))
    {
    ?>
        <section class="vitrine-marca cf" id="botaoFiltro">
            <?= ($tipoBusca == "marca") ? "<img src=\"" . $detalheMarca['Logo'] ."\" />" : "" ?>
            <div class="filtro-botao"><a href="#">FILTROS</a></div>
        </section>

        <section class="vitrine-produtos cf">
            <div class="container fixvitrine">

                <!-- facets -->
                <div class="categoria-menu-esquerdo fixfiltro" id="filtro-conteudo">
                    <div class="categoria-menu-esquerdo-filtro">
                        <h4>Filtros</h4>
                        <span id="countResult"><?= (!empty($resultadoBuscaCat)) ? count($resultadoBuscaCat) : "0" ?> itens</span>
                        <button type="button" onclick="limparFiltro()">Limpar Filtros</button>
                    </div>
                    <div class="panel-group categoria-filtros" id="categoria-filtros">
                        <form name="filtrosBusca" id="filtrosBusca" method="post" action="/" onsubmit="false">
                        <?php
                        if (!empty($precos))
                        {
                        ?>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#categoria-filtros-tamanho" class="tab-toggle collapsed">
                                            Faixa de preço
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
                        <?php
                        }
                        ?>
                            
                        <?php
                        
                        function odernacaoOpcoes($a, $b)
                        {
                            return strcmp($a['Valor'], $b['Valor']);
                        }
                        
                        function odernacaoFiltro($a, $b)
                        {
                            if ($a['Posicao'] == $b['Posicao']) 
                            {
                                return 0;
                            }
                            return ($a['Posicao'] < $b['Posicao']) ? -1 : 1;                            
                        }
                        
                        if (!empty($filtros))
                        {
                            usort($filtros, "odernacaoFiltro"); // ordenas as caracteristicas por ordem alfabetica                        
                        }
                        
                        $i = 0;
                        foreach ((array) $filtros as $filtro)
                        {
                        ?>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#categoria-filtros-<?= $i ?>" class="tab-toggle collapsed">
                                            <?= $filtro['Descricao'] ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="categoria-filtros-<?= $i ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php
                                        if (!empty($filtro['Opcoes']))
                                        {
                                            usort($filtro['Opcoes'], "odernacaoOpcoes"); // ordenas as opções
                                        }
                                        
                                        foreach ((array) $filtro['Opcoes'] as $opcao)
                                        {
                                        ?>
                                            <label><input type="checkbox" name="<?= $opcao['ValorID'] ?>" value="<?= $opcao['ValorID'] ?>" onclick="filtrarBusca(-1);"> <?= $opcao['Valor'] ?></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $i ++;
                        }
                        ?>
                            <input type="hidden" name="posttermobusca" id="posttermobusca" value="<?= $postBusca ?>">
                            <input type="hidden" name="posttipofiltro" id="posttipofiltro" value="<?= md5("buscaProduto") ?>">
                            <input type="hidden" name="posttipobusca" id="posttipobusca" value="<?= $tipoBusca ?>">
                            <input type="hidden" name="postordenacao" id="postordenacao" value="-1">
                            <input type="hidden" name="postvalormin" id="postvalormin" value="<?= $minPreco ?>">
                            <input type="hidden" name="postvalormax" id="postvalormax" value="<?= $maxPreco ?>">
                        </form>
                    </div>
                </div>
                <!-- end facets -->            

                <div id="gridBusca">
                    <ul class="list-inline" id="itensGrid">
                    </ul>
                </div>
            </div>
        </section>
    <?php
    }
    ?>
    
    <script type="text/javascript">
        $('#gridBusca').html('<?= $esperaResultado ?>');
        
        $.post('/_pages/filtro.php', {posttipobusca:'<?= $tipoBusca ?>',
                                      posttermobusca:'<?= $postBusca ?>',
                                      posttipofiltro:'<?= md5("buscaProduto") ?>'},
        function(resultadoBusca)
        {
            $('#gridBusca').html(resultadoBusca);
        });
    </script>
    
    <?php
    if ($tipoBusca == "marca")
    {
    ?>
        <section class="vitrine-sobre">
        <div>
            <h4>Sobre <?= $detalheMarca['Descricao'] ?></h4>
            <p>
                <?= $detalheMarca['DescricaoDetalhada'] ?>
            </p>
        </div>
        </section>
    <?php
    }
    ?>
<?php
}
?>

<section class="descubra">
    <h3>Descubra Mais</h3>
    <form class="form-inline" name="buscarefinada" method="get" action="/busca">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> </button>
            </div>
            <input class="form-control" type="text" name="termobusca" placeholder="o que você procura ?" required="required" />
        </div>
    </form>	
</section>