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
        <h3>Por favor informe um termo para busca.</h3>
    </div>
<?php
}
else
{
    $esperaResultado = '<div style="height: 400px; text-align:center; padding: 100px 0; "><i class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></i></div>';
    
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
    
    foreach ((array) $resultadoBuscaCat as $produtoBuscaCat)
    {
        foreach ((array) $produtoBuscaCat['Caracteristicas'] as $caracteristica)
        {
            if (!array_key_exists($caracteristica['TipoID'] . "##" . $caracteristica['Descricao'], $filtros)) 
            {
                $filtros[$caracteristica['TipoID'] . "##" . $caracteristica['Descricao']] = []; // se nao existir array de oções, cria-o.
            }
            
            array_push($filtros[$caracteristica['TipoID'] . "##" . $caracteristica['Descricao']], $caracteristica['ValorID'] . "##" . $caracteristica['Valor']); // insere o valor no array de opções e cada caracteristicas
        }
    }
    
    $filtrosUnicos = [];
    
    for ($i = 0; $i < count($filtros); $i++) // varre o array e retirar os duplicados, colocando o resultado em um novo array
    {
        $filtrosUnicos[array_keys($filtros)[$i]] = array_unique($filtros[array_keys($filtros)[$i]]);
    }
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
            <div class="container">    

                <!-- facets -->
                <div class="categoria-menu-esquerdo" id="filtro-conteudo">
                    <div class="categoria-menu-esquerdo-filtro">
                        <h4>Filtros</h4>
                        <span id="countResult"><?= (!empty($resultadoBuscaCat)) ? count($resultadoBuscaCat) : "0" ?> itens</span>
                        <button type="button" onclick="limparFiltro()">Limpar Filtros</button>
                    </div>
                    <div class="panel-group categoria-filtros" id="categoria-filtros">
                        <form name="filtrosBusca" id="filtrosBusca" method="post" action="/" onsubmit="false">
                        <?php
                        for ($i = 0; $i < count($filtrosUnicos); $i ++)
                        {
                            $tipoFiltro = explode("##", array_keys($filtrosUnicos)[$i]);
                            
                            if ($tipoFiltro[1] == "Vitrine" || strpos($tipoFiltro[1], "GENÉRICA") > 0 || strpos($tipoFiltro[1], "GENERICA") > 0 ) continue;
                        ?>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#categoria-filtros-<?= $i ?>" class="tab-toggle collapsed">
                                            <?= $tipoFiltro[1] ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="categoria-filtros-<?= $i ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php
                                        foreach ((array) $filtrosUnicos[array_keys($filtrosUnicos)[$i]] as $opcao)
                                        {
                                            $valorFiltro = explode("##", $opcao);
                                        ?>
                                            <label><input type="checkbox" name="<?= $valorFiltro[0] ?>" value="<?= $valorFiltro[0] ?>" onclick="filtrarBusca(-1);"> <?= $valorFiltro[1] ?></label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                            <input type="hidden" name="posttermobusca" value="<?= $postBusca ?>">
                            <input type="hidden" name="posttipofiltro" value="<?= md5("buscaProduto") ?>">
                            <input type="hidden" name="posttipobusca" value="<?= $tipoBusca ?>">
                            <input type="hidden" name="postordenacao" value="-1">
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