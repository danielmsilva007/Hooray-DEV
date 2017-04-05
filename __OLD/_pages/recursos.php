<?php

if (!defined('HoorayWeb')) 
{
    die;
}

$descricaoRecursos = getRest($endPoint['rodape']);

?>

<section class="header-inst clearfix">

    <div class="header-int-wrap">

        <h4>Recursos</h4>

        <p class="text-center">
            Conte com nosso material de apoio, caso não encontre o que procura,<br />
            não exite em procurar no campo abaixo e se necessário entre em contato com nosso staff. <a href="#">Pedir ajuda</a>!
        </p>

        <div class="descubra">
            <form class="form-inline">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> </button>
                    </div>
                    <input class="form-control" type="" name="" placeholder="O que você procura?" value="Qual a sua dúvida?" />
                </div>
            </form>
        </div>

        <div class="recursos-square">
            <div class="recursos-square-content">
                <div class="recursos-square-table">
                    <div class="recursos-table-cell">
                        <a data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-como-comprar">
                            Como comprar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="recursos-square">
            <div class="institucional-square-content">
                <div class="institucional-square-table">
                    <div class="institucional-table-cell">
                        <a data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-troca-dev">
                            Trocas e devoluções
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="recursos-square">
            <div class="institucional-square-content">
                <div class="institucional-square-table">
                    <div class="institucional-table-cell">
                        <a data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-politica-entrega">
                            Política de entrega
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="recursos-square">
            <div class="institucional-square-content">
                <div class="institucional-square-table">
                    <div class="institucional-table-cell">
                        <a data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-status">
                            Status do pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    
    </div>

    <div class="make-space-bet clearfix"></div>

</section>
   
<section class="conteudo-int">
    <div class="panel-group recursos-accordion" id="recursos-accordion">

        <?php
        foreach ((array) $descricaoRecursos as $recursos)
        {
            if (in_array($recursos['ID'], ['5','8']))
            {
                foreach ((array) $recursos['Itens'] as $itemRecurso)
                {
        ?>
                    <div class="panel">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a name="recursos-accordion-como-comprar" data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-como-comprar" class="cursor-toggle">
                                    <?= $itemRecurso['Descricao'] ?>
                                </a>
                            </h4>
                        </div>
                        <div id="recursos-accordion-como-comprar" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <?= $itemRecurso['Html'] ?>
                            </div>
                        </div>
                    </div>
        <?php
                }
            }
        }
        ?>

    </div>

</section>

<div class="make-space-bet clearfix"></div>

