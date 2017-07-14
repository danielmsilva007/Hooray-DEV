<?php
if (!defined('HoorayWeb')) 
{
    die;
}

if (!empty($dadosLogin['CarrinhoId']))
{
    $IDCarrinho = $dadosLogin['CarrinhoId'];
}
elseif (!empty($_SESSION['carrinho']))
{
    $IDCarrinho = $_SESSION['carrinho'];
}
else
{
    $IDCarrinho = -1;
}    

$esperaResultado = '<div class="panel-heading">Atualizando seu carrinho...</div>'
                 . '<div class="row">'
                 . '<div class="cart-box-col-left"></div>'
                 . '<div class="cart-box-col-center-mob">'
                 . '<br><i class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></i>'
                 . '</div>'
                 . '<div class="cart-box-col-right"></div>'
                 . '<div class="make-space-bet"></div>'
                 . '</div>';
?>
<script type="text/javascript">
    function retirarCarrinho(IDProduto)
    {
        $('#dadosCarrinho').html('<?= $esperaResultado ?>');

        $.post('/_pages/carrinhoEditar.php', {postidproduto:IDProduto,
                                              postidcarrinho:'<?= $IDCarrinho ?>',
                                              postcarrinho:'<?= md5("editCarrinho") ?>',
                                              posttipoedicao:'<?= md5("remover") ?>',
                                              posttipocarrinho:'<?= md5("pagina") ?>'},
        function(dataCarrinho)
        {
            $('#dadosCarrinho').html(dataCarrinho);
        });                
    }
    
    function atualizarQtde(IDProduto)
    {
        var qtdeAlterar = document.getElementById("qtdeItemCarriho" + IDProduto).value;

        $.post('/_pages/carrinhoEditar.php', {postidproduto:IDProduto,
                                              postqtdeproduto:qtdeAlterar,
                                              postidcarrinho:'<?= $IDCarrinho ?>',
                                              postcarrinho:'<?= md5("editCarrinho") ?>',
                                              posttipoedicao:'<?= md5("alterarqdte") ?>',
                                              posttipocarrinho:'<?= md5("pagina") ?>'},
        function(dataCarrinho)
        {
            $('#dadosCarrinho').html(dataCarrinho);
            atualizarFrete();
        });                
    }    
    
    function atualizarFrete()
    {
        var CEPCarrinho = $('#CEPCarrinho').val();
        var CEPCompCarrinho = $('#CEPCompCarrinho').val();
        
        $('#atualizandoCEP').html('Atualizando...');
        
        $.post('/_pages/carrinhoEditar.php', {postidcarrinho:'<?= $IDCarrinho ?>',
                                              postcepcarrinho: CEPCarrinho + '-' + CEPCompCarrinho,
                                              posttipoedicao:'<?= md5("calcularCEP") ?>',
                                              postcarrinho:'<?= md5("editCarrinho") ?>',
                                              posttipocarrinho:'<?= md5("pagina") ?>'},
        function(dataCarrinho)
        {
            $('#dadosCarrinho').html(dataCarrinho);
        });
        
        $('#atualizandoCEP').html('');
        
        return false;
    }        
</script>

<section class="cart">
    <h4>Carrinho</h4>
    <div class="cart-box" id="dadosCarrinho">
        <?php
        if (empty($IDCarrinho) || $IDCarrinho <= 0)
        {
        ?>
            <div class="panel-heading">Seu carrinho está vazio</div>
            <div class="row">
                <div class="cart-box-col-left"></div>
                <div class="cart-box-col-center-mob">
                    <br>Não há produtos no seu carrinho.
                </div>
                <div class="cart-box-col-right"></div>
                
                <div class="make-space-bet"></div>
            </div>            
        <?php
        }
        ?>
    </div>
</section>

<?php
if (!empty($IDCarrinho) && $IDCarrinho > 0)
{
?>
    <script type="text/javascript">
        $('#dadosCarrinho').html('<?= $esperaResultado ?>');
        
        $.post('/_pages/carrinhoEditar.php', {postidcarrinho:'<?= $IDCarrinho ?>',
                                              postcarrinho:'<?= md5("editCarrinho") ?>',
                                              posttipoedicao:'<?= md5("atualizar") ?>',
                                              posttipocarrinho:'<?= md5("pagina") ?>'},        
        function(dataCarrinho)
        {
            $('#dadosCarrinho').html(dataCarrinho);
        });                                            
    </script>    

    <div class="footer-cart">
        <div class="footer-cart-cep clearfix">
            <form name="consultarCEP" id="consultarCEP" method="post" action="/carrinho" onsubmit="return atualizarFrete();" >
            <div>
                <p>Consulte o frete</p>
                <p>Por favor informe o CEP</p>
            </div>
            <div>
                <input type="text" name="CEPCarrinho" id="CEPCarrinho" placeholder="00000" maxlength="5" required="required" /> - <input type="text" name="CEPCompCarrinho" id="CEPCompCarrinho" placeholder="000" maxlength="3" required="required" />
            </div>
            <div>
                <button type="submit" class="btn">Consultar</button>
                <i id="atualizandoCEP"></i>
            </div>
            <div>
                    <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/BuscaCepEndereco.cfm" target="_blank">Não sei o meu CEP</a>
            </div>
            </form>
			
        </div>

        <div class="footer-cart-mailling clearfix">
            <form method="post" action="/checkout">
                <label><input type="checkbox" name="mailling" value="1">Quero receber ofertas e desconto por e-mail</label>
                <div class="ordem-botao-finalizar-bottom">
                    <button type="submit" class="btn btn-lg btn-primary">Finalizar a compra</button>
                </div>
            </form>
			   <!--Evandro script RD Station 31-05-17 -->
				<script type="text/javascript" src="https://d335luupugsy2.cloudfront.net/js/integration/stable/rd-js-integration.min.js"></script>  
				<script type="text/javascript">
					var meus_campos = {
						'mailling': 'mailling'
					 };
					options = { fieldMapping: meus_campos };
					RdIntegration.integrate('19be3ce6a7bd0b40fbe376160c87784f', 'ofertas', options);  
				</script>
				<!--Evandro script RD Station 31-05-17 -->
        </div>
    </div>
<?php
}
?>

<div class="make-space-bet clearfix"></div>
<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/e88341a9-780f-4d0c-8ebc-b5d4463ef21f-loader.js"></script>