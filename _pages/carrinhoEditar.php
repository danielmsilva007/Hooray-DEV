<?php
$phpPost = filter_input_array(INPUT_POST);

define('HoorayWeb', TRUE);

include_once ("../p_settings.php");

if (!empty($phpPost['postcarrinho']) && $phpPost['postcarrinho'] == md5("addCarrinho"))
{
    if (empty($phpPost['postqdteproduto']) || !is_numeric($phpPost['postqdteproduto']))
    {
        die ("!!Por favor informe a quantidade.");
    }
    
    $dadosProdCarrinho = getRest(str_replace("{IDProduto}", $phpPost['postidproduto'], $endPoint['produto']));

    $skusProduto = $dadosProdCarrinho['Skus'];

    if (count($skusProduto) == 1)
    {
        $skuCarrinho = $skusProduto[0]['ID'];
    }
    else
    {
        $descritorFiltro = ($phpPost['postdescritor'] == -1) ? null : $phpPost['postdescritor'];
        $descritorFiltro2 = ($phpPost['postdescritor2'] == -1) ? null : $phpPost['postdescritor2'];
        $descritorFiltro3 = ($phpPost['postdescritor3'] == -1) ? null : $phpPost['postdescritor3'];
        $descritorFiltro4 = ($phpPost['postdescritor4'] == -1) ? null : $phpPost['postdescritor4'];
        $descritorFiltro5 = ($phpPost['postdescritor5'] == -1) ? null : $phpPost['postdescritor5'];

        foreach ((array) $skusProduto as $skubusca)
        {
            if ($skubusca['Descritor'] == $descritorFiltro &&
                $skubusca['Descritor2'] == $descritorFiltro2 &&
                $skubusca['Descritor3'] == $descritorFiltro3 &&
                $skubusca['Descritor4'] == $descritorFiltro4 &&
                $skubusca['Descritor5'] == $descritorFiltro5)
            {
                $skuCarrinho = $skubusca['ID'];
                break;
            }
        }
    }
    
    $itemExiste = -1;
    
    if ($phpPost['postidcarrinho'] > 0)
    {
        $verificarItemNoCarrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
        
        foreach ((array) $verificarItemNoCarrinho['Itens'] as $itemVerificar)
        {
            if ($itemVerificar['SkuID'] == $skuCarrinho)
            {
                $itemExiste = $itemVerificar['Id'];
                break;
            }
        }
    }
    
    if ($itemExiste > 0) // Caso já exista no carrinho, retira-o e o adicina com a nova quantidade.
    {
        $removerDoCarriho = sendRest(str_replace("{IDItem}", $itemExiste, $endPoint['delcarrinho']), [], "DELETE");
    }

    $dadosCarrinho = ["CarrinhoID" => ($phpPost['postidcarrinho'] > 0) ? $phpPost['postidcarrinho'] : NULL,
                      "SkuID" => $skuCarrinho,
                      "Quantidade" => $phpPost['postqdteproduto']
        ];

    $retrornoCarrinho = sendRest($endPoint['addcarrinho'], $dadosCarrinho, "POST");

    if (!empty($retrornoCarrinho['Erro']) && $retrornoCarrinho['Erro'] == true)
    {
        die ("!!" . $retrornoCarrinho['Mensagem']);
    }
    
    if ($phpPost['postidlogin'] > 0)
    {
        $dadosLoginCarrinho = ["CarrinhoID" => $retrornoCarrinho['Dados']['CarrinhoID'],
                               "LoginID" => $phpPost['postidlogin']
            ];
        
        $associarCarrinho = sendRest($endPoint['addlogincarrinho'], $dadosLoginCarrinho, "PUT");
    }
    else
    {
        session_start();
        $_SESSION['carrinho'] = $retrornoCarrinho['Dados']['CarrinhoID'];
    }

    $carrinho = getRest(str_replace("{IDCarrinho}", $retrornoCarrinho['Dados']['CarrinhoID'], $endPoint['obtercarrinho']));
?>
    <div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </div>
    <div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>
    <div class="nav">
        <a href="/carrinho">IR PARA O CARRINHO</a>
    </div>
    <ul>
        <?php 
        foreach ((array) $carrinho['Itens'] as $itemCarrinho)
        {
        ?>
            <li id="itemCarinhoModal<?= $itemCarrinho['Id'] ?>">
                <div class="row">
                    <div class="col-xs-3">
                        <img src="<?= $itemCarrinho['ProdutoImagemMobile'] ?>" title="<?= $itemCarrinho['ProdutoDescricao'] ?>" />
                    </div>
                    <div class="col-xs-7">
                        <p>
                            <?= $itemCarrinho['ProdutoDescricao'] ?><br>
                            <?= $itemCarrinho['Marca'] ?><br>
                            Quantidade: <?= $itemCarrinho['Quantidade'] ?><br>
                            <span><?= formatar_moeda($itemCarrinho['ValorTotal']) ?></span>
                            <i id="resultDelCarrinho<?= $itemCarrinho['Id'] ?>"></i>
                        </p>
                    </div>
                    <div class="col-xs-2 text-right">		        				
                        <!--<a href="#"><span aria-hidden="true">&times;</span></a>-->
                        <a href="javascript:retirarCarrinhoModal('<?= $itemCarrinho['Id'] ?>');" title="Retirar do carrinho"><img src="/images/site/i-close-red-med.png"></a>&nbsp;&nbsp;
                    </div>
                </div>
            </li>
        <?php
        }
        ?>
    </ul>
    <form method="post" action="/carrinho">
        <button type="submit" class="btn btn-primary btn-lg">CHECKOUT</button>
    </form>
<?php    
}

if (!empty($phpPost['postcarrinho']) && $phpPost['postcarrinho'] == md5("editCarrinho"))
{
    if ($phpPost['posttipoedicao'] == md5("remover") && empty($phpPost['postidproduto']))
    {
        echo "!!Produto não encontrado no carrinho.";
        die;
    }

    if ($phpPost['posttipoedicao'] == md5("remover"))
    {
        $removerItem = sendRest(str_replace("{IDItem}", $phpPost['postidproduto'], $endPoint['delcarrinho']), [], "DELETE");
    }
    elseif ($phpPost['posttipoedicao'] == md5("alterarqdte"))
    {
        $dadosQtde = ["CarrinhoItemID" => $phpPost['postidproduto'],
                      "Quantidade" => $phpPost['postqtdeproduto']
            ];
        
        $atualizarQuantidade = sendRest($endPoint['qtdecarrinho'], $dadosQtde, "PUT");
    }

    if ($phpPost['posttipocarrinho'] == md5("modal"))
    {
        echo "Atualizando o carrinho...";
    }
    elseif ($phpPost['posttipocarrinho'] == md5("pagina"))
    {
	if ($phpPost['posttipoedicao'] == md5("calcularCEP"))
        {
            $CEPCalculo = preg_replace('/\D/', '', $phpPost['postcepcarrinho']);
            
            $dadosCEPCarrinho = ["CarrinhoID" => $phpPost['postidcarrinho'],
                                 "CEP" => $CEPCalculo
                ];
            
            $carrinho = sendRest($endPoint['fretecarrinho'], $dadosCEPCarrinho, "PUT");
            
            if (!empty($carrinho['Message']))
            {
                $carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
            }
        }
        else
        {
            $carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
        }

	$itensFornecedor = [];

	foreach ((array) $carrinho['Itens'] as $item) // agrupa itens por fornecedor
	{
            if (!array_key_exists($item['Fornecedor'], $itensFornecedor))
            {
                $itensFornecedor[$item['Fornecedor']] = ["Fornecedor" => $item['Fornecedor'],
                                                         "Itens" => []
                    ];
            }

            array_push($itensFornecedor[$item['Fornecedor']]['Itens'], $item);
	}

        foreach ((array) $itensFornecedor as $fornecedor)
        {
        ?>
            <div class="panel-heading">Carrinho <?= $fornecedor['Fornecedor'] ?> <span>(<?= count($fornecedor['Itens']) ?> <?= (count($fornecedor['Itens']) > 1) ? "Itens" : "Item" ?>)</span></div>

            <?php
            $i = 0;
            foreach ((array) $fornecedor['Itens'] as $itemFornecedor)
            {
                if ($i != 0) echo "<div class=\"line-bet\"></div>";
            ?>
                <div class="row">
                    <div class="cart-box-col-left">
                        <img src="<?= $itemFornecedor['ProdutoImagemMobile'] ?>" title="<?= $itemFornecedor['ProdutoDescricao'] ?>">
                        <span class="small-text">
                            <?= $itemFornecedor['ProdutoDescricao'] ?><br>
                            <span class="small-text-grey">Marca:</span> <?= $itemFornecedor['Marca'] ?><br>
                            <span class="small-text-grey">Valor unitário:</span> <?= formatar_moeda($itemFornecedor['ValorUnit']) ?><br>
                            <span class="small-text-grey">Vendido e entregue por:</span> <?= $itemFornecedor['Fornecedor'] ?>
                        </span>
                        <br>
                        <a href="javascript:retirarCarrinho('<?= $itemFornecedor['Id'] ?>');" class="glyphicon glyphicon-trash"><span class="text-btn"> Excluir</span></a>
                    </div>

                    <div class="cart-box-col-center-mob">
                        <div class="cart-box-col-center">
                            <form class="form-inline" name="qdtProd" id="qdtProd" method="post" onsubmit="false">
                                <div class="form-group" style="text-align: center;">
                                    <label>Quantidade</label>
                                    <input type="number" name="qtdeItemCarriho<?= $itemFornecedor['Id'] ?>" id="qtdeItemCarriho<?= $itemFornecedor['Id'] ?>" value="<?= $itemFornecedor['Quantidade'] ?>" class="form-control" min="1">
                                    <a href="javascript:atualizarQtde('<?= $itemFornecedor['Id'] ?>');"><span class="text-btn"> Atualizar</span></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="cart-box-col-right">
                        <span id="totalitem">Total item: <?= formatar_moeda($itemFornecedor['ValorTotal']) ?></span>
                        <span>Frete: <?= (is_numeric($itemFornecedor['ValorFrete'])) ? formatar_moeda($itemFornecedor['ValorFrete']) : "Não calculado" ?></span>
                        <span>Previsão de entrega: <?= date_format(date_create($itemFornecedor['DataEntrega']), "d/m/Y") ?></span>
                    </div>
                </div>
        <?php
               $i ++;
            }
        }
        ?>
        <!-- footer panel -->
        <div class="panel-footer ordem-pagamento-revisao-total">
            <div class="row">
                <div class="col-xs-4">
                    Resumo do carrinho
                </div>
                <div class="col-xs-8">
                    <div class="ordem-pagamento-revisao-total-valor">
                        <i>Itens: <?= formatar_moeda($carrinho['SubTotal']) ?></i><br />
                        <i>Frete: <?= (is_numeric($carrinho['Frete'])) ? formatar_moeda($carrinho['Frete']) : "Não calculado" ?></i><br />
                        Total: <?= formatar_moeda($carrinho['Total']) ?><br />
                    </div>
                </div>
            </div>
        </div>
        <!-- end footer panel -->        
<?php        
    }
}
?>