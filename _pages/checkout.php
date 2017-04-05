<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$esperaResultado = '<div align="center"><span class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></div>';

// Enderços de entrega
$enderecos = getRest(str_replace("{IDParceiro}", $dadosLogin['Parceiro']['ID'], $endPoint['endcadastral']));
$enderecoCarrinho = (!empty($enderecos['Enderecos'][1]['ID'])) ? $enderecos['Enderecos'][1] : $enderecos['Enderecos'][0];
?>
<section class="checkpoint">
    <div class="row">
        <div class="linha">
            <div class="col-xs-4">
                <div class="circulo"></div>
                Identificação
            </div>
            <div class="col-xs-4">
                <div class="circulo active"></div>
                Endereço e Pagamento
            </div>
            <div class="col-xs-4">
                <div class="circulo"></div>
                Confirmação
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function GerarHash() 
    {
        var hash;
        
        var cc = new Moip.CreditCard({
            number: $("#pgNumCartao").val(),
            cvc: $("#pgCVC").val(),
            expMonth: $("#pgMedVenc").val(),
            expYear: $("#pgAnoVenc").val(),
            pubKey: '<?= MOIPPublicKey ?>'
        });
        if (cc.isValid())
        {
            hash = cc.hash();
        }
        else 
        {
            hash = '!!Cartão de crédito inválido.';
        }
        
        return hash;
    }
</script>

<script type="text/javascript">
    function alterarEndCarrinho()
    {
        var IDEndEntrega = $('#tipoEndEntrega').val();
        
        $('#enderecoEntregaCarrinho').html('<?= $esperaResultado ?>');
        $('#opcoesEntregaCarrinho').html('<?= $esperaResultado ?>');
        $('#formaPgtoCarrinho').html('<?= $esperaResultado ?>');
        $('#retornoResumoCarrinho').html('<?= $esperaResultado ?>');
        $('#retornoTotalCarrinho').html('<?= $esperaResultado ?>');
        
        $.post('/_pages/checkoutEditar.php', {postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
                                              postidendereco:IDEndEntrega,
                                              postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
                                              posttipoedicao:'<?= md5("enderecoEntrega") ?>'},
        function(resultadoEndereco)
        {
            $('#enderecoEntregaCarrinho').html(resultadoEndereco);
        });
        
        
        $.post('/_pages/checkoutEditar.php', {postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
                                              postidendereco:IDEndEntrega,
                                              postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
                                              posttipoedicao:'<?= md5("opcoesEntrega") ?>'},
        function(resultadoOpcoes)
        {
            $('#opcoesEntregaCarrinho').html(resultadoOpcoes);
        });
        

        $.post('/_pages/checkoutEditar.php', {postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
                                              postidendereco:IDEndEntrega,
                                              postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
                                              posttipoedicao:'<?= md5("opcoesPagto") ?>'},
        function(resultadoPgto)
        {
            $('#formaPgtoCarrinho').html(resultadoPgto);
        });


        $.post('/_pages/checkoutEditar.php', {postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
                                              postidendereco:IDEndEntrega,
                                              postidcarrinho:'<?= $dadosLogin['CarrinhoId'] ?>',
                                              posttipoedicao:'<?= md5("resumoCarrinho") ?>'},
        function(resultadoResumoCarrinho)
        {
            $('#retornoResumoCarrinho').html(resultadoResumoCarrinho);
        });
    }
    
    function finalizarCompra()
    {
        var opcaoForma = $('input[name=opcoesforma]:checked', '#chechoutForm').val();
        var hash;
        var finalizar = false;
        
        if (opcaoForma == '0')
        {
            hash = GerarHash();
            
            if (hash.substr(0,2) == "!!")
            {
                $('#retornoCheckout').html(hash.substr(2));
            }
            else
            {
                $('#retornoCheckout').html('');
                $('#pgHash').val(hash);
                finalizar = true;
            }
        }
        else
        {
            $('#retornoCheckout').html('');
            finalizar = true;
        }
        
        if (finalizar)
        {
            $.post('/_pages/checkoutEditar.php', $("#chechoutForm").serialize(),
            function(resultadoFinalizarCompra)
            {
                $('#retornoFinalizarCompra').html(resultadoFinalizarCompra);
            }); 
            
        }
    }
</script>

<section class="ordem">
    <div id="retornoFinalizarCompra">
        <form name="chechoutForm" id="chechoutForm" method="post" action="/checkout" onsubmit="false">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">1. ENDEREÇO PARA ENTREGA</div>
                        <div class="panel-body">
                            <label>Selecione o endereço</label>
                            <div class="form-group input-icone">
                                <select name="tipoEndEntrega" id="tipoEndEntrega" class="form-control" onchange="alterarEndCarrinho();">
                                    <?php
                                    foreach ((array) $enderecos['Enderecos'] as $endEntrega)
                                    {
                                        $selecionado = ($enderecoCarrinho['ID'] == $endEntrega['ID']) ? " SELECTED" : "";
                                        echo '<option value="' . $endEntrega['ID'] . '"' . $selecionado . '>' . $tipoEndereco[$endEntrega['Tipo']] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>                        
                            <div id="enderecoEntregaCarrinho"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default" id="accordion">
                        <div class="panel-heading">2. OPÇÕES DE ENTREGA</div>
                        <div class="panel-body ordem-pagamento-entrega" id="opcoesEntregaCarrinho"></div>
                    </div>
                    <div class="panel panel-default ordem-forma-pagamento">
                        <div class="panel-heading">3. FORMA DE PAGAMENTO</div>
                        <div class="panel-body" id="formaPgtoCarrinho"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">4. REVISÃO DO PEDIDO</div>
                        <div id="retornoResumoCarrinho"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="posttipoedicao" id="posttipoedicao" value="<?= md5("finalizarCompra") ?>">
            <input type="text" name="pgHash" id="pgHash" value="0">
        </form>

        <script type="text/javascript">
            alterarEndCarrinho();
        </script>
    </div>
</section>