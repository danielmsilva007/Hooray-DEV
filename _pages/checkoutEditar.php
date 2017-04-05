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
    
    $i = 0;
    foreach ((array) $opcoesFornecedor as $opPorFornec)
    {
        $i++;
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
                                <div class="col-xs-5"><input type="radio" name="opEntrega<?= $i ?>" id="opEntrega<?= $i ?>" value="<?= $opcoes['LocalidadeTransporteID'] ?>" onchange="opcoesEntrega('opEntrega<?= $i ?>');" /> <?= $opcoes['DescricaoServico'] ?></div>
                                <div class="col-xs-4"><?= (!empty($opcoes['FreteGratis'])) ? "Frete Grátis" : formatar_moeda($opcoes['ValorCobrarCliente']) ?></div>
                                <div class="col-xs-3 text-right"><?= date_format(date_create($opcoes['DataEntrega']), "d/m/y") ?></div>
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
    }
    ?>
    <input type="hidden" name="opNumOpcoes" id="opNumOpcoes" value="<?= $i ?>">
    <p>Seu pedido será liberado logo após a confirmação do pagamento.</p>    
<?php    
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("opcoesPagto"))
{
    $carrinho = getRest(str_replace('{IDCarrinho}', $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
    
    $parcelamento = getRest(str_replace(['{IDCarrinho}','{valorCarrinho}'], [$phpPost['postidcarrinho'], $carrinho['Total']], $endPoint['parcarrinho']));
    
    echo "<option disabled selected>Número de parcelas</option>";
    foreach ((array) $parcelamento as $parcela)
    {
        if ($parcela['Numero'] == 0 || $parcela['PagamentoMetodoFormaID'] != $phpPost['portidbandeira']) continue;

        echo "<option value=\"" . $parcela['PagamentoMetodoFormaID'] . "\">" . $parcela['Numero'] . " x de " . formatar_moeda($parcela['Valor']) . " sem juros</option>";
    }    
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("resumoCarrinho"))
{
    $carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
?>
    <div class="row">
        <div class="col-sm-6">Subtotal: </div>
        <div class="col-sm-6 text-right"><?= formatar_moeda($carrinho['SubTotal']) ?></div>
    </div>
    <div class="row">
        <div class="col-sm-6">Entrega: </div>
        <div class="col-sm-6 text-right"><?= (!empty($carrinho['Frete'] && is_numeric($carrinho['Frete']))) ? formatar_moeda($carrinho['Frete']) : formatar_moeda(0) ?></div>
    </div>
<?php
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("totalCarrinho"))
{
    $carrinho = getRest(str_replace("{IDCarrinho}", $phpPost['postidcarrinho'], $endPoint['obtercarrinho']));
?>
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
<?php
}

if (!empty($phpPost['posttipoedicao']) && $phpPost['posttipoedicao'] == md5("totalCarrinho"))
{
    
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

    //$finalizarPedido = sendRest($endPoint['checkout'], $dadosPedido, "POST");
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