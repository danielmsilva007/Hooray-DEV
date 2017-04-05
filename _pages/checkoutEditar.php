<?php
$phpPost = filter_input_array(INPUT_POST);

define('HoorayWeb', TRUE);

include_once ("../p_settings.php");

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("enderecoEntrega"))
{
    $endSelCarrinho = getRest(str_replace("{IDParceiro}", $phpPost['postidparceiro'], $endPoint['endcadastral']));
    
    foreach ((array) $endSelCarrinho['Enderecos'] as $endCarrinho)
    {
        if ($endCarrinho['ID'] == $phpPost['postidendereco'])
        {
            $dadosCarrinho = ["CarrinhoID" => $phpPost['postidcarrinho'],
                              "CEP" => $endCarrinho['CEP']
                ];
            $carrinhoComFrete = sendRest($endPoint['fretecarrinho'], $dadosCarrinho, "PUT");
?>
           <div class="form-group input-icone">
               <input type="text" class="form-control" name="endDestinatario" id="endDestinatario" placeholder="Nome" value="<?= $endCarrinho['Destinatario'] ?>" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endCEP" id="endCEP" placeholder="CEP" value="<?= $endCarrinho['CEP'] ?>" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endRua" id="endRua" value="<?= $endCarrinho['Logradouro'] ?>" placeholder="Endereço" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endNum" id="endNum" value="<?= $endCarrinho['Numero'] ?>" placeholder="Número" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endComp" id="endComp" value="<?= $endCarrinho['Complemento'] ?>" placeholder="Completemento" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endBairro" id="endBairro" value="<?= $endCarrinho['Bairro'] ?>" placeholder="Bairro" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endCidade" id="endCidade" value="<?= $endCarrinho['Cidade']['Nome'] ?>" placeholder="Cidade" disabled="disabled" />
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="endUF" id="endUF" value="<?= $endCarrinho['Cidade']['Estado']['Sigla'] ?>" placeholder="Referêmcia" disabled="disabled" />
           </div>       
<?php
            break;
        }
    }
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("opcoesEntrega"))
{
    $endSelCarrinho = getRest(str_replace("{IDParceiro}", $phpPost['postidparceiro'], $endPoint['endcadastral']));
    
    foreach ((array) $endSelCarrinho['Enderecos'] as $endCarrinho)
    {
        if ($endCarrinho['ID'] == $phpPost['postidendereco'])
        {
            $cepOpcao = $endCarrinho['CEP'];
            break;
        }
    }

    // Opções de entrega por seller
    $dadosServEntrega = ["CarrinhoID" => $phpPost['postidcarrinho'],
                         "CEP" => $cepOpcao
        ];
    $opcoesEntrega = sendRest($endPoint['servicoentrega'], $dadosServEntrega, "PUT");

    $opcoesFornecedor = [];
    foreach ((array) $opcoesEntrega as $opEntrega) // Agrupa por os seviços por seller
    {
        if (!array_key_exists($opEntrega['FornecedorID'], $opcoesFornecedor))
        {
            $opcoesFornecedor[$opEntrega['FornecedorID']] = ["NomeFornecedor" => $opEntrega['NomeFornecedor'],
                                                             "Opcoes" => []
                ];
        }
        array_push($opcoesFornecedor[$opEntrega['FornecedorID']]['Opcoes'], $opEntrega);
    }
    
    $i = 1;
    foreach ((array) $opcoesFornecedor as $opPorFornec)
    {
    ?>
        <p>
            <a data-toggle="collapse" data-parent="#accordion" href="#opcao-<?= $i ?>"><b class="caret"></b> Carrinho <?= $opPorFornec['NomeFornecedor'] ?></a>
        </p>
        <div id="opcao-<?= $i ?>" class="panel-collapse collapse">
            <ul>
                <?php
                $ii = 1;
                foreach ((array) $opPorFornec['Opcoes'] as $opcoes)
                {
                ?>
                    <li>
                        <label>
                            <div class="row">
                                <div class="col-xs-5"><input type="radio" name="opEntrega<?= $i ?>" <?= ($ii == 1) ? " checked" : "" ?> value="<?= $opcoes['LocalidadeTransporteID'] ?>" /> <?= $opcoes['DescricaoServico'] ?></div>
                                <div class="col-xs-3"><?= (!empty($opcoes['FreteGratis'])) ? "Frete grátis" : formatar_moeda($opcoes['ValorCobrarCliente']) ?></div>
                                <div class="col-xs-2 text-right"><?= date_format(date_create($opcoes['DataEntrega']), "d/m/y") ?></div>
                            </div>
                        </label>
                    </li>
                <?php
                    $ii ++;
                }
                ?>
            </ul>
        </div>
    <?php
        $i ++;
    }
    ?>
    <p>Seu pedido será liberado logo após a confirmação do pagamento.</p>    
<?php    
}


if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("opcoesPagto"))
{
    $carrinhoPgto = getRest(str_replace('{IDCarrinho}', $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
    
    $parcelamento = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$phpPost['postidcarrinho'], $carrinhoPgto['Total']], $endPoint['parcarrinho']));

    $parBoleto = array_search("0", array_column($parcelamento, 'Numero')); // busca pela parcela 0 (boleto)
    
    if (isset($parBoleto) && is_numeric($parBoleto))
    {
        $boletoHabititado = true;
?>
        <div class="form-group">
            <label><input type="radio" name="opcoesforma" id="opcoesforma" value="2" checked> Boleto Bancário</label>
        </div>
<?php
    }
    else 
    {
        $boletoHabititado = false;
    }
?>
    <div class="form-group">
        <label><input type="radio" name="opcoesforma" id="opcoesforma" value="0" <?= ($boletoHabititado) ? "" : " checked" ?>> Cartão de crédito</label>
    </div>
    <div class="form-group input-icone">
        <input type="text" name="pgNumCartao" id="pgNumCartao" class="form-control"  placeholder="Número no cartão" required="required" maxlength="16" />
        <i class="glyphicon glyphicon-credit-card"></i>
    </div>
    <div class="form-group">
    </div>    
    <div class="form-group">
        <div class="input-group">
            <select class="form-control" name="pgParcela" id="pgParcela" required="required">
                <option disabled selected>Número de parcelas</option>
                <?php
                foreach ((array) $parcelamento as $parcela)
                {
                    if ($parcela['Numero'] == 0) continue;

                    echo "<option value=\"" . $parcela['PagamentoMetodoFormaID'] . "\">" . $parcela['Numero'] . " x de " . formatar_moeda($parcela['Valor']) . " sem juros</option>";
                }
                ?>
            </select>
            <span class="input-group-addon">
                <a data-toggle="tooltip" data-placement="top" title="Por favor selecione a quantidade de parcelas para a sua compra.">
                    <i class="glyphicon glyphicon-info-sign"></i>
                </a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="pgNomeCartao" id="pgNomeCartao" placeholder="Nome impresso no cartão" required="required">
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                <select class="form-control" name="pgMedVenc" id="pgMedVenc" required="required">
                    <option disabled selected>Mês</option>
                    <?php
                    for ($i = 1; $i <= count($meses); $i++)
                    {
                        echo "<option value=\" . $i . \">" . $meses[$i] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                <select class="form-control" name="pgAnoVenc" id="pgAnoVenc" required="required">
                    <option disabled selected>Ano</option>
                    <?php
                    for ($i = date("Y"); $i < (date("Y") + 11); $i++)
                    {
                        echo "<option value=\" . $i . \">" . $i . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>	
    <div class="form-group">
        <div class="input-group">
            <input name="pgCVC" id="pgCVC" type="password" class="form-control" placeholder="Código de segurança" required="required" maxlength="3" />
            <span class="input-group-addon">
                <a data-toggle="tooltip" data-placement="top" title="Por favor informe o código de segurança do cartão.">
                    <i class="glyphicon glyphicon-info-sign"></i>
                </a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="pgSalvarCartao" value="1" checked="checked"> Salvar cartão para a próxima compra!</label>
    </div>
    <i>Para efetuar o pagamento, não há necessidade de salvar seu cartão. Esta função apenas facilita suas próximas compras com toda segurança.</i>

<?php   
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("resumoCarrinho"))
{
    $carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
?>
    <div class="panel-body ordem-pagamento-revisao">
        <ul>
            <?php
            foreach ((array) $carrinho['Itens'] as $item)
            {
            ?>
            <li>
                <div class="row">
                    <div class="col-xs-3">
                        <img src="<?= $item['ProdutoImagemMobile'] ?>" title="<?= $item['ProdutoDescricao'] ?>"/>	
                    </div>
                    <div class="col-xs-5">
                        <p>
                            <?= $item['ProdutoDescricao'] ?><br>
                            Quantidade: <?= $item['Quantidade'] ?>
                        </p>
                    </div>
                    <div class="col-xs-4">
                        <div class="text-right">
                            <?= formatar_moeda($item['ValorTotal']) ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
        <div class="ordem-pagamento-revisao-subtotal">
            <div class="row">
                <div class="col-sm-6">Subtotal: </div>
                <div class="col-sm-6 text-right"><?= formatar_moeda($carrinho['SubTotal']) ?></div>
            </div>
            <div class="row">
                <div class="col-sm-6">Entrega: </div>
                <div class="col-sm-6 text-right"><?= (!empty($carrinho['Frete'] && is_numeric($carrinho['Frete']))) ? formatar_moeda($carrinho['Frete']) : formatar_moeda(0) ?></div>
            </div>
        </div>
    </div>
    <div class="panel-footer ordem-pagamento-revisao-total">
    <div class="row">
        <div class="col-xs-4">
            &nbsp;&nbsp;Total:
        </div>
        <div class="col-xs-8">
            <div class="ordem-pagamento-revisao-total-valor">
                <?= formatar_moeda($carrinho['Total']) ?>&nbsp;&nbsp;<br /><br />
            </div>
        </div>
    </div>
    <div id="retornoCheckout"></div>
    <div class="ordem-botao-finalizar-bottom">
        <button type="button" onclick="finalizarCompra();" class="btn btn-lg btn-primary">Finalizar a compra</button>
    </div>
</div>
<?php
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("finalizarCompra"))
{
    
    $dadosPedido = ["LoginID" => $dadosLogin['ID'],
                    "EnderecoID"  => 2100, // TODO ID 
                    "PagamentoMetodoFormaID"  => 7, //TODO
                    "ClassificacaoPagto"  => 0, // 0 = cartão, 2 = boleto
                    "Parcela"  => 1, //TODO numero de parcelas
                    "CarrinhoID"  => $dadosLogin['CarrinhoId'],
                    "CupomDesconto"  => null,
                    "DadosCartao"  => [
                            "Titular" => $phpPost['pgNomeCartao'],
                            "Numero" => $phpPost['pgCartao'],
                            "Mes" => "05",
                            "Ano" => $phpPost['pgAnoVend'],
                            "CodigoSeguranca"  => $phpPost['pgCodSeg'],
                            "Hash" => "FTh60Z1p29ef9oO8BM119KL1JijZW3K6nXa4kFAotGXpKUSgBbQhA2z19qJHH0I8XFMKuxr5AgKzHQ+umA0R8tMz0zEElKj4mnLBN3ejmPj0PQk9gpbMkoUt/72oJGLL0YqMnuRW9FGnpSWMLiDAOe+PfMVi32wOiNWTBh21da1ucdPYbyPfrS3ia9JH91L0sblwcK/cboYw3uQBygV+HoGc6vF9yl259GfckRhM6EOHbVDUbf/2pd4vpw4RT5MZrbcihd34HG6g+uwZFwb+TBuphUpzFp4FIdLQjKcR3ILcfgFa79wHXbIV6LN+wP+R91Jok56S38k/BtAAleZYOA=="
                    ],
    ];

    $finalizarPedido = sendRest($endPoint['checkout'], $dadosPedido, "POST");
?>

    <h3 class="text-center"><i class="glyphicon glyphicon-ok"></i> Seu pedido foi concluído com sucesso!</h3>
    <div class="row">
        
        <pre> 
        <?php
        print_r($phpPost);
        ?>
        </pre>
        
    </div>
<?php
}
?>