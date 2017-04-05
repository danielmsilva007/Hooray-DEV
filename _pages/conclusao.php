<?php
if (!defined('HoorayWeb')) 
{
    die;
}
$carrinho = getRest(str_replace("{IDCarrinho}", $dadosLogin['CarrinhoId'], $endPoint['obtercarrinho']));

$enderecos = getRest(str_replace("{IDParceiro}", $dadosLogin['Parceiro']['ID'], $endPoint['endcadastral']));

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

<section class="checkpoint">
    <div class="row">
        <div class="linha">
            <div class="col-xs-4">
                <div class="circulo"></div>
                Identificação
            </div>
            <div class="col-xs-4">
                <div class="circulo"></div>
                Endereço e Pagamento
            </div>
            <div class="col-xs-4">
                <div class="circulo active"></div>
                Conclusão
            </div>
        </div>
    </div>
</section>

<section class="ordem">
    <h3 class="text-center"><i class="glyphicon glyphicon-ok"></i> Seu pedido foi concluído com sucesso!</h3>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">FORMA DE PAGAMENTO</div>
                <div class="panel-body">
                    <div class="ordem-pagamento-concluido-forma">
                        Cartão de crédito no valor de:
                        <span>Total de <?= formatar_moeda($carrinho['Total']) ?></span>
<!--                        <span>3x R$ 942,40</span>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">PEDIDO № <?= $finalizarPedido['ID'] ?></div>
                <div class="panel-body ordem-pagamento-concluido-pedido">
                    Acabamos de enviar o número do pedido para:
                    <span><?= $dadosLogin['Email'] ?></span>
                    Endereço de entrega
                    <span><?= $enderecos['Enderecos'][0]['Logradouro'] ?>, <?= $enderecos['Enderecos'][0]['Numero'] ?><br />
                        <?= $enderecos['Enderecos'][0]['CEP'] ?> - <?= $enderecos['Enderecos'][0]['Bairro'] ?><br />
                        <?= $enderecos['Enderecos'][0]['Cidade']['Nome'] ?> - <?= $enderecos['Enderecos'][0]['Cidade']['Estado']['Sigla'] ?></span>
<!--                    <div>
                        <a class="btn btn-lg btn-primary">Acompanhe seu pedido</a>
                    </div>-->
                </div>
            </div>
        </div>
    </div>

</section>