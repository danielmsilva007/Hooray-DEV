<?php
$phpPost = filter_input_array(INPUT_POST);

define('HoorayWeb', TRUE);

include_once ("../p_settings.php");

if (!empty($phpPost['posttipofiltro']) && $phpPost['posttipofiltro'] == md5("buscaProduto"))
{
    $dadosBusca = ["Chave" => ($phpPost['posttipobusca'] == "busca") ? $phpPost['posttermobusca'] : "",
                    "Count" => "-1",
                    "UltimoID" => -1,
                    "UltimoPreco" => -1,
                    "UltimaDescricao" => "",
                    "TipoOrdenacao" => (isset($phpPost['postordenacao']) && $phpPost['postordenacao'] >= 0) ? $phpPost['postordenacao'] : -1,
                    "ProdutoID" => -1,
                    "SecaoID" => ($phpPost['posttipobusca'] == "secao") ? $phpPost['posttermobusca'] : -1,
                    "MarcaID" => ($phpPost['posttipobusca'] == "marca") ? $phpPost['posttermobusca'] : -1,
                    "ProdutoCategoriaID" => ($phpPost['posttipobusca'] == "categoria") ? $phpPost['posttermobusca'] : -1,
                    "ProdutoSubCategoriaID" => -1
        ];
    
    $caracteristicasFiltro = [];
    
    for ($i = 0; $i < count($phpPost); $i++)
    {
        if (in_array(array_keys($phpPost)[$i], ['posttermobusca', 'posttipofiltro', 'postordenacao', 'posttipobusca'])) continue; // variaveis de controle que nao entram no filtro.
        
        $caracteristicasFiltro[$i] = ["ValorID" => $phpPost[array_keys($phpPost)[$i]]];
    }
    
    if (!empty($caracteristicasFiltro))
    {
        $dadosBusca['CaracteristicasFiltro'] = $caracteristicasFiltro;
    }
    
    $resultadoBusca = sendRest($endPoint['buscaestendida'], $dadosBusca, "POST");
    
    echo "<ul class=\"list-inline\">";
    foreach ((array) $resultadoBusca as $produtoBusca)
    {
    ?>    
        <li>
            <a href="/produto?id=<?= $produtoBusca['ID'] ?>">
                <div class="list-wrapper">
                    <img class="img-responsive" src="<?= $produtoBusca['Imagem'] ?>" />
                    <div class="vitrine-produtos-titulo"><?= $produtoBusca['Descricao'] ?></div>
                    <div class="vitrine-produtos-marca"><?= $produtoBusca['Marca']['Descricao'] ?></div>
                    <div class="vitrine-produtos-preco"><?= (!empty($produtoBusca['PrecoDePor'])) ? "<s>" . formatar_moeda($produtoBusca['PrecoDePor']['PrecoDe']). "</s> | " : "" ?><b><?= formatar_moeda($produtoBusca['PrecoVigente']) ?></b></div>
                    <?php
                        $vitrine = "&nbsp";
                        foreach ((array) $produtoBusca['Caracteristicas'] as $caracteristica)
                        {
                            if ($caracteristica['Descricao'] == "Vitrine")
                            {
                                $vitrine = $caracteristica['Valor'];
                            }
                        }
                    ?>
                    <div class="vitrine-produtos-tamanho"><?= $vitrine ?></div>
                </div>
            </a>
        </li>
<?php    
    }
    echo "</ul>";
}
?>
