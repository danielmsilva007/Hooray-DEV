<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$paginaAjuda = getRest($endPoint['rodape']);

$phpGet = filter_input_array(INPUT_GET);

if (!empty($phpGet))
{
    $secaoPagina = $phpGet[array_keys($phpGet)[0]];
}

?>

<section class="header-inst clearfix">

    <div class="header-int-wrap">

        <h4>Ajuda</h4>

        <p class="text-center">
            Conte com nosso material de apoio.<br />
            Caso não encontre o que procura, por favor entre em contato com a nossa equipe, através da nossa <a href="/contato">área atendimento</a>.
        </p>
        
        <?php
        foreach ((array) $paginaAjuda as $ajuda)
        {
            if (in_array($ajuda['ID'], ['5'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
            {
                foreach ((array) $ajuda['Itens'] as $item)
                {
                    if (in_array($item['ID'],['24','25','26','45','57'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
                    {
        ?>       
                        <div class="recursos-square">
                            <div class="recursos-square-content">
                                <div class="recursos-square-table">
                                    <div class="recursos-table-cell">
                                        <a class="ancora" data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-<?= $item['ID'] ?>">
                                            <?= $item['Descricao'] ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
        <?php
                    }
                }
            }
        }
        ?>        
    </div>

    <div class="make-space-bet clearfix"></div>

</section>

<section class="conteudo-int">
    <div class="panel-group recursos-accordion" id="recursos-accordion">

        <?php
        foreach ((array) $paginaAjuda as $ajuda)
        {
            if (in_array($ajuda['ID'], ['5'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
            {
                foreach ((array) $ajuda['Itens'] as $item)
                {
                    if (in_array($item['ID'],['24','25','26','45','57'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
                    {
        ?>
                    <a name="<?= $item['ID'] ?>"></a>
                    <div class="panel">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a name="recursos-accordion-<?= $item['ID'] ?>" data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-<?= $item['ID'] ?>" class="cursor-toggle">
                                    <?= $item['Descricao'] ?>
                                </a>
                            </h4>
                        </div>
                        <div id="recursos-accordion-<?= $item['ID'] ?>" class="panel-collapse collapse<?= (!empty($secaoPagina) && $secaoPagina == $item['ID']) ? " in" : "" ?>"> <?php // abre a secao quando a ID da secao informada no URL for igual ao ID da secao corrente. ?>
                            <div class="panel-body">
                                 <?= $item['Html'] ?>
                            </div>
                        </div>
                    </div>
        <?php
                    }
                }
            }
        }
        ?>
    </div>
</section>

<div class="make-space-bet clearfix"></div>