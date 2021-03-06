<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$elementosSEO = (!empty($paginas[2])) ? explode("-", $paginas[2]) : [];

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
    //$esperaResultado = '<table width="800" height="400"><tr><td align="center" valign=middle"><span class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></span></td></tr></table>';
    
    switch ($tipoBusca)
    {
        case "busca": 
            $postBusca = $termoBusca; 
            break;
        
        case "secao":
            $postBusca = $IDSecao;
            $indiceSecao = array_search($IDSecao, array_column($menuSite, 'SecaoID'));
            $descricaoSecao = $menuSite[$indiceSecao]['Descricao'];
            break;

        case "marca":
            $postBusca = end($elementosSEO);
            break;

        case "categoria":
            $postBusca = $IDCategoria; 
            break;        
    }
    
    $infoBreadcrumb = ["busca" => ["URL" => "/busca", 
                                   "Descricao" => "Busca"],
                       "secao" => ["URL" => "/", 
                                   "Descricao" => (!empty($descricaoSecao)) ? $descricaoSecao : ""],
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
                    "MarcaID" => ($tipoBusca == "marca") ? end($elementosSEO) : -1,
                    "ProdutoCategoriaID" => ($tipoBusca == "categoria") ? $IDCategoria : -1,
                    "ProdutoSubCategoriaID" => -1
        ];
    
    $resultadoBuscaCat = sendRest($endPoint['buscaestendida'], $dadosBuscaCat, "POST");
    
    $filtros = [];
    $filtrosNumerico = [];
    $opcoesDeslizantes = [];
    $precos = [];
    $volumes = [];
    
    foreach ((array) $resultadoBuscaCat as $produtoBuscaCat)
    {
        foreach ((array) $produtoBuscaCat['Caracteristicas'] as $caracteristica)
        {
            if (!$caracteristica['Filtro']) continue; // só adiciona filtros marcados com TRUE
            
            if (empty($caracteristica['Posicao']))
            {
                $caracteristica['Posicao'] = 999999999; // não definida posição
            }
            
            if ($caracteristica['Numerico']) // deslizante
            {
                if (!array_key_exists($caracteristica['TipoID'], $filtrosNumerico)) //cria a chave com o tipo, se nao existir
                {
                    $filtrosNumerico[$caracteristica['TipoID']] = ["TipoID" => $caracteristica['TipoID'],
                                                                   "ValorID" => $caracteristica['ValorID'],
                                                                   "Descricao" => $caracteristica['Descricao'],
                                                                   "Posicao" => $caracteristica['Posicao'],
                                                                   "Numerico" => $caracteristica['Numerico'],
                                                                   "Opcoes" => []
                        ];
                }

                if (!array_key_exists($caracteristica['ValorID'], $filtrosNumerico[$caracteristica['TipoID']]['Opcoes'])) // cria o valor, se nao houver
                {
                    $opcaoFiltroNumerico = ["ValorID" => $caracteristica['ValorID'],
                                            "Valor" => str_replace([".",","], [",","."], $caracteristica['Valor'])
                        ];

                    $filtrosNumerico[$caracteristica['TipoID']]['Opcoes'][$caracteristica['ValorID']] = $opcaoFiltroNumerico;
                }
                
                if (!array_key_exists($caracteristica['TipoID'], $opcoesDeslizantes))
                {
                    $opcoesDeslizantes[$caracteristica['TipoID']] = [];
                }
                
                array_push($opcoesDeslizantes[$caracteristica['TipoID']], floatval(str_replace([".",","], [",","."], $caracteristica['Valor'])));
                
                if ($caracteristica['TipoID'] == $IDDeslizanteVolume)
                {
                    array_push($volumes, floatval(str_replace([".",","], [",","."], $caracteristica['Valor'])));
                }
            }

            if (!array_key_exists($caracteristica['TipoID'], $filtros)) //cria a chave com o tipo, se nao existir
            {
                $filtros[$caracteristica['TipoID']] = ["TipoID" => $caracteristica['TipoID'],
                                                       "ValorID" => $caracteristica['ValorID'],
                                                       "Descricao" => $caracteristica['Descricao'],
                                                       "Posicao" => $caracteristica['Posicao'],
                                                       "Numerico" => $caracteristica['Numerico'],
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
    
    $minPreco = (!empty($precos) && is_array($precos)) ? floor(min($precos)) : 0;
    $maxPreco = (!empty($precos) && is_array($precos)) ? ceil(max($precos)) : 0;
    
    $minVolume = (!empty($volumes) && is_array($volumes)) ? min($volumes) : 0;
    $maxVolume = (!empty($volumes) && is_array($volumes)) ? max($volumes) : 0;    

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
            $('#postvalormin').val(<?= $minPreco ?>);
            $('#postvalormax').val(<?= $maxPreco ?>);
    
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
                
                //$('#countResult').html($(resultadoBusca).children().length + item);
                $('#countResult').html($('#gridBusca ul li').length + item);
                snapSlider.noUiSlider.set([<?= $minPreco ?>,<?= $maxPreco ?>]);
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
                
                //$('#countResult').html($(resultadoBusca).children().length + item);
                $('#countResult').html($('#gridBusca ul li').length + item);
            });
            
            
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
                                        <a data-toggle="collapse" href="#categoria-filtro-preco" class="tab-toggle collapsed">
                                            Faixa de preço
                                        </a>
                                    </h4>
                                </div>
                                <div id="categoria-filtro-preco" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="handles-wrap">
                                            <div id="slider-handles"></div>
                                            <div id="moedamin">R$&nbsp;</div><div id="slider-snap-value-lower"></div><div id="decmin">,00</div>
                                            <div id="slider-snap-value-until">até</div>
                                            <div id="moedamax">R$&nbsp;</div><div id="slider-snap-value-upper"></div><div id="decmax">,00</div>
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
                        
                        ?>
                        <?php
                            if (!empty($volumes))
                            {
                        ?>
                            <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#categoria-filtros-volume" class="tab-toggle collapsed">
                                        Volume
                                    </a>
                                </h4>
                            </div>
                            <div id="categoria-filtros-volume" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="handles-wrap">
		                      	<div class="handles-wrap">
                                            <div id="slider-handles2"></div>
                                            <div id="slider-snap-value-lower2"></div>
                                            <div id="slider-snap-value-until2">até</div>
                                            <div id="slider-snap-value-upper2"></div>
		                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                            
                        <?php
                        
                        $i = 1;
                        
                        foreach ((array) $filtros as $filtro)
                        {
                        ?>
                            <div class="panel"<?= (!empty($filtro['Numerico'])) ? " style=\"DISPLAY: none\"" : "" ?>>
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
                                            <label><input type="checkbox" name="<?= $filtro['TipoID'] . "_" . $opcao['ValorID'] ?>" value="<?= $filtro['TipoID'] . '##' . $opcao['ValorID'] . '##' . str_replace([".",","], [",","."], $opcao['Valor']) ?>" onclick="filtrarBusca(-1);"<?= (!empty($filtro['Numerico']))? " CHECKED" : "" ?>> <?= $opcao['Valor'] ?></label>
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
                            <input type="hidden" name="postvolumemin" id="postvolumemin" value="<?= $minVolume ?>">
                            <input type="hidden" name="postvolumemax" id="postvolumemax" value="<?= $maxVolume ?>">                            
                        </form>
                    </div>
				
                </div>
                <!-- end facets -->            

                <div id="gridBusca" class="busca-centro">
                    <ul class="list-inline" id="itensGrid">
                    </ul>
                </div>
            </div>
        </section>
    <?php
    }
    ?>
    
    <script type="text/javascript">
        filtrarBusca(-1);
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
				<!--Evandro script RD Station 31-05-17 -->
				<script type="text/javascript" src="https://d335luupugsy2.cloudfront.net/js/integration/stable/rd-js-integration.min.js"></script>  
				<script type="text/javascript">
					var meus_campos = {
						'buscarefinada': 'termobusca'
					 };
					options = { fieldMapping: meus_campos };
					RdIntegration.integrate('19be3ce6a7bd0b40fbe376160c87784f', 'BuscaResposnsiva', options);  
				</script>
				<!--Evandro script RD Station 31-05-17 -->
</section>
<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/e88341a9-780f-4d0c-8ebc-b5d4463ef21f-loader.js"></script>