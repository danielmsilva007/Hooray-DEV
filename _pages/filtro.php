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
        if (in_array(array_keys($phpPost)[$i], ['posttermobusca', 'posttipofiltro', 'postordenacao', 'posttipobusca', 'postvalormin', 'postvalormax'])) continue; // variaveis de controle que nao entram no filtro.
        
        $opcoesFiltro = explode('##', $phpPost[array_keys($phpPost)[$i]]);
        
        $caracteristicasFiltro[$i] = ["TipoID" => $opcoesFiltro[0],
                                      "ValorID" => $opcoesFiltro[1]
            ];
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
    
    echo "<ul class=\"list-inline\" id=\"itensGrid\">";
    if (!empty($resultadoBusca['ProdutoBuscaItens']) && count($resultadoBusca['ProdutoBuscaItens']) > 0)
    {
        foreach ((array) $resultadoBusca['ProdutoBuscaItens'] as $produtoBusca)
        {
        ?>    
            <li>
                <a href="/produto?id=<?= $produtoBusca['ID'] ?>">
                    <div class="list-wrapper">
                        <img class="img-responsive" src="<?= $produtoBusca['Imagem'] ?>" />
                        <div class="vitrine-produtos-titulo"><?= $produtoBusca['Descricao'] ?></div>
                        <div class="vitrine-produtos-marca"><?= $produtoBusca['Marca'] ?></div>
                        <?php
                        //$indexEmEstoque = array_search("0", array_column($produtoBusca['Skus'], 'Disponibilidade'));
                        //$indexSobEncomenda = array_search("1", array_column($produtoBusca['Skus'], 'Disponibilidade'));
                        //$indexIndisponivel = array_search("2", array_column($produtoBusca['Skus'], 'Disponibilidade'));
                        //if ($indexEmEstoque === false && $indexSobEncomenda === false) // nao retornou em estoque nem sobencomenda
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
                        //if (!empty($produtoBusca['Caracteristicas']))
                        //{
                        //    $indexVitrine = array_search("Vitrine", array_column($produtoBusca['Caracteristicas'], 'Descricao'));
                        //}
                        //$vitrine = (!empty($indexVitrine) && is_numeric($indexVitrine)) ? $produtoBusca['Caracteristicas'][$indexVitrine]['Valor'] : "&nbsp;";
                        $vitrine = "&nbsp;";
                        ?>
                        <div class="vitrine-produtos-tamanho"><?= $vitrine ?></div>
                    </div>
                </a>
            </li>
    <?php    
        }
    }
    else
    {
    ?>
        <li>
            <div class="list-wrapper">
                <img class="img-responsive" src="/images/site/buscasemresultados.png" />
            </div>
        </li>
    <?php
    }
    echo "</ul>";
}
?>
