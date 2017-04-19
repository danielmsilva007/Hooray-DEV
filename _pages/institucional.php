<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$paginaInstitucional = getRest($endPoint['rodape']);

$phpGet = filter_input_array(INPUT_GET);

if (!empty($phpGet))
{
    $secaoPagina = $phpGet[array_keys($phpGet)[0]];
}

?>

<section class="header-inst clearfix">

    <div class="header-int-wrap">

        <h4>Institucional</h4>

        <p class="text-center">
            Conte com nosso material de apoio.<br />
            Caso não encontre o que procura, por favor entre em contato com a nossa equipe, através da nossa <a href="/contato">área atendimento</a>.
        </p>

        <?php
        foreach ((array) $paginaInstitucional as $institucional)
        {
            if (in_array($institucional['ID'], ['8'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
            {
                foreach ((array) $institucional['Itens'] as $item)
                {
                    if (in_array($item['ID'],['41','42','47','48'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
                    {
        ?>       
                        <div class="institucional-square">
                            <div class="institucional-square-content">
                                <div class="institucional-square-table">
                                    <div class="institucional-table-cell">
                                        <?php
                                        if ($item['ID'] == 41) 
                                        {
                                        ?>
                                            <a class="ancora" href="/contato">
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                            <a class="ancora" data-toggle="collapse" data-parent="#institucional-accordion" href="#institucional-accordion-<?= $item['ID'] ?>">
                                        <?php
                                        }
                                        ?>
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
    <div class="panel-group institucional-accordion" id="institucional-accordion">
        
        <?php
        foreach ((array) $paginaInstitucional as $institucional)
        { 
            if (in_array($institucional['ID'], ['8'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
            {
                foreach ((array) $institucional['Itens'] as $item)
                {
                    if (in_array($item['ID'],['42','47','48'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
                    {
        ?>
                    <a name="<?= $item['ID'] ?>"></a>
                    <div class="panel">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a name="institucional-accordion-<?= $item['ID'] ?>" data-toggle="collapse" data-parent="#institucional-accordion" href="#institucional-accordion-<?= $item['ID'] ?>" class="cursor-toggle">
                                    <?= $item['Descricao'] ?>
                                </a>
                            </h4>
                        </div>
                        <div id="institucional-accordion-<?= $item['ID'] ?>" class="panel-collapse collapse<?= (!empty($secaoPagina) && $secaoPagina == $item['ID']) ? " in" : "" ?>"> <?php // abre a secao quando a ID da secao informada no URL for igual ao ID da secao corrente. ?>
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