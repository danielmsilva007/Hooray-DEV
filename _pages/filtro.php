<?php
$phpPost = filter_input_array(INPUT_POST);

define('HoorayWeb', TRUE);

include_once ("../p_settings.php");

if (!empty($phpPost['posttipofiltro']) && $phpPost['posttipofiltro'] == md5("buscaProduto"))
{
    $dadosBusca = ["Chave" => ($phpPost['posttipobusca'] == "busca") ? $phpPost['posttermobusca'] : "",
                   "Count" => "9999",
                   "UltimoID" => -1,
                   "UltimoPreco" => -1,
                   "UltimaDescricao" => "",
                   "TipoOrdenacao" => (isset($phpPost['postordenacao']) && $phpPost['postordenacao'] >= 0) ? $phpPost['postordenacao'] : 0, // se nao informada a ordernação, orderna pela descrição
                   "ProdutoID" => -1,
                   "SecaoID" => ($phpPost['posttipobusca'] == "secao") ? $phpPost['posttermobusca'] : -1,
                   "MarcaID" => ($phpPost['posttipobusca'] == "marca") ? $phpPost['posttermobusca'] : -1,
                   "ProdutoCategoriaID" => ($phpPost['posttipobusca'] == "categoria") ? $phpPost['posttermobusca'] : -1,
                   "ProdutoSubCategoriaID" => -1
        ];
    
    $caracteristicasFiltro = [];

    for ($i = 0; $i < count($phpPost); $i++)
    {
        if (in_array(array_keys($phpPost)[$i], ['posttermobusca', 
                                                'posttipofiltro', 
                                                'postordenacao', 
                                                'posttipobusca', 
                                                'postvalormin', 
                                                'postvalormax',
                                                'postvolumemin',
                                                'postvolumemax']
                )) continue; // variaveis de controle que nao entram no filtro.
        
        $opcoesFiltro = explode('##', $phpPost[array_keys($phpPost)[$i]]);
        
        if (($opcoesFiltro[0] != $IDDeslizanteVolume) || ($opcoesFiltro[0] == $IDDeslizanteVolume && $opcoesFiltro[2] >= $phpPost['postvolumemin'] && $opcoesFiltro[2] <= $phpPost['postvolumemax']))
        {
            
            $dadosCaracteristicas = ["TipoID" => $opcoesFiltro[0],
                                    "ValorID" => $opcoesFiltro[1]
                    ];
            
            array_push($caracteristicasFiltro, $dadosCaracteristicas);
        }
    }

    if (!empty($caracteristicasFiltro))
    {
        $dadosBusca['CaracteristicasFiltro'] = $caracteristicasFiltro;
    }
    
    if (!empty($phpPost['postvalormin']) && !empty($phpPost['postvalormax']))
    {
        $faixaPreco = ["PrecoInicial" => floor($phpPost['postvalormin']),
                       "PrecoFinal" => ceil($phpPost['postvalormax'])
            ];
        
        $dadosBusca['FaixaPreco'] = $faixaPreco;
    }

    $resultadoBusca = sendRest($endPoint['busca'], $dadosBusca, "POST");

    if (!empty($resultadoBusca['ProdutoBuscaItens']) && count($resultadoBusca['ProdutoBuscaItens']) > 0)
    {
        echo "<ul class=\"list-inline\" id=\"itensGrid\">";
        
        $contFiltro = 0;
        foreach ((array) $resultadoBusca['ProdutoBuscaItens'] as $produtoBusca)
        {
            if (!empty($produtoBusca['PrecoDe']) && $produtoBusca['PrecoDe'] > 0)
            {
                $label = " p-label-off" . $contFiltro;
                $content = floor($produtoBusca['PercentualDesconto']) . "% OFF";
                $color = "#ff6666";
            }
            elseif (!empty($produtoBusca['Lancamento']))
            {
                $label = " p-label-new" . $contMV;
                $content = "NEW";
                $color = "#66b3ff";                
            }
            else
            {
                $label = "";
                $content = "";
                $color = "";
            }
        ?>    
            <li>
                <a href="/produto/<?= $produtoBusca['SEO'] ?>">
                    <div class="list-wrapper<?= $label ?>">
                        <img class="img-responsive" src="<?= $produtoBusca['Imagem'] ?>" />
                        <div class="vitrine-produtos-titulo"><?= $produtoBusca['Descricao'] ?></div>
                        <div class="vitrine-produtos-marca"><?= $produtoBusca['Marca'] ?></div>
                        <?php
                        if ($produtoBusca['Disponibilidade'] == 2)
                        {
                        ?>
                            <div class="vitrine-produtos-preco"><b>Indisponível</b></div>
                        <?php
                        }
                        else
                        {
                        ?>
                            <div class="vitrine-produtos-preco"><?= (!empty($produtoBusca['PrecoDe'])) ? "<s>" . formatar_moeda($produtoBusca['PrecoDe']). "</s> | " : "" ?><b><?= formatar_moeda($produtoBusca['PrecoVigente']) ?></b></div>
                        <?php
                        }
                        $vitrine = (!empty($produtoBusca['SubCategoriaDescricao'])) ? $produtoBusca['SubCategoriaDescricao']  : "&nbsp;";
                        ?>
                        <div class="vitrine-produtos-tamanho"><?= $vitrine ?></div>
                        <?php
                        if (!empty($label))
                        {
                        ?>
                            <style type="text/css">
                                    .list-inline div.<?= trim($label) ?>::before{
                                        content: "<?= $content ?>" !important;
                                        position: absolute;
                                        top: 0;
                                        left: 5px;
                                        color: <?= $color ?>;
                                        font-weight: lighter;
                                        font-family: 'PT Sans Narrow', sans-serif;
                                    }
                            </style>                            
                        <?php
                        }
                        ?>
                    </div>
                </a>
            </li>
    <?php    
            $contFiltro ++;
        }
        echo "</ul>";
    }
    else
    {
    ?>
        <div class="list-wrapper">
            <img class="img-responsive" src="/images/site/buscasemresultados.png" />
        </div>
    <?php
    }
}
?>
<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/e88341a9-780f-4d0c-8ebc-b5d4463ef21f-loader.js"></script>