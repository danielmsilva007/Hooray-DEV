<?php

if (!defined('HoorayWeb'))
{
    die;
}

$dadosProduto = getRest(str_replace("{IDProduto}", $IDProduto, $endPoint['produto']));
// TODO tratar retorno de produto nao existente

?>

<section class="site-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="/"><span class="glyphicon glyphicon-menu-left"></span>Voltar</a> 
            </div>
            <div class="col-md-10">
                <a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
                <a href="#"><?= $dadosProduto['Categoria']['Descricao'] ?></a> <span class="glyphicon glyphicon-menu-right"></span>
                <a href="#"><?= $dadosProduto['Marca']['Descricao'] ?></a> <span class="glyphicon glyphicon-menu-right"></span>
                <?= $dadosProduto['Descricao'] ?>
            </div>
        </div>
    </div>
</section>

<section class="produto">
    <div class="container">
        <div class="row">

            <!-- GALERIA INICIO -->
            <div class="col-md-6">

                <div class="produto-galeria-imagem">
                    <img src="<?= $dadosProduto['ImagemPlus1'] ?>" class="zoom" data-magnify-src="./images/produto-imagem-prancha-01-large.png">
                </div>
                <div class="row produto-galeria-thumb">
                    <div class="col-xs-3"><img src="<?= $dadosProduto['ImagemPlus1'] ?>" /></div>
                    <div class="col-xs-3"><img src="<?= $dadosProduto['ImagemPlus2'] ?>" /></div>
                    <div class="col-xs-3"><img src="<?= $dadosProduto['ImagemPlus3'] ?>" /></div>
                    <div class="col-xs-3"><img src="<?= $dadosProduto['ImagemPlus4'] ?>" /></div>
                </div> 
            </div>
            <!-- GALERIA FIM //-->
            
            <div class="col-md-6">
                <div class="produto-descricao">
                    <h4><?= $dadosProduto['Descricao'] ?></h4>
                    <p>Marca: <span><?= $dadosProduto['Marca']['Descricao'] ?></span></p>
                    <p>Vendido e entregue por: <span><?= $dadosProduto['Fornecedor'] ?></span></p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="produto-descricao-preco">
                                <?= (!empty($dadosProduto['PrecoDePor'])) ? "<s>" . formatar_moeda($dadosProduto['PrecoDePor']['PrecoDe']) . "</s><br>" : "" ?>
                                <?= formatar_moeda($dadosProduto['PrecoVigente']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label>Quantidade</label>
                                    <input type="number" class="form-control" min="0" class="input-lg" name="">
                                </div>
                                
                                <?php
                                if (!empty($dadosProduto['TipoDescritor']))
                                {
                                ?>
                                    <div class="form-group">
                                        <label><?= $dadosProduto['TipoDescritor']['Descricao'] ?></label></br>
                                        <select class="form-control" min="0" class="input-lg" name="<?= $dadosProduto['TipoDescritor']['ID'] ?>">
                                            <?php
                                            foreach ((array) $dadosProduto['Skus'] as $sku)
                                            {
                                                echo "<option value=\"" . $sku['ID'] . "\">" . $sku['Descritor'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>
                                
                                <?php
                                if (!empty($dadosProduto['TipoDescritor2']))
                                {
                                ?>
                                    <div class="form-group">
                                        <label><?= $dadosProduto['TipoDescritor2']['Descricao'] ?></label></br>
                                        <select type="number" class="form-control" min="0" class="input-lg" name="">
                                            <?php
                                            foreach ((array) $dadosProduto['Skus'] as $sku)
                                            {
                                                echo "<option value=\"" . $sku['ID'] . "\">" . $sku['Descritor2'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if (!empty($dadosProduto['TipoDescritor3']))
                                {
                                ?>
                                    <div class="form-group">
                                        <label><?= $dadosProduto['TipoDescritor3']['Descricao'] ?></label></br>
                                        <select type="number" class="form-control" min="0" class="input-lg" name="">
                                            <?php
                                            foreach ((array) $dadosProduto['Skus'] as $sku)
                                            {
                                                echo "<option value=\"" . $sku['ID'] . "\">" . $sku['Descritor3'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if (!empty($dadosProduto['TipoDescritor4']))
                                {
                                ?>
                                    <div class="form-group">
                                        <label><?= $dadosProduto['TipoDescritor4']['Descricao'] ?></label></br>
                                        <select type="number" class="form-control" min="0" class="input-lg" name="">
                                            <?php
                                            foreach ((array) $dadosProduto['Skus'] as $sku)
                                            {
                                                echo "<option value=\"" . $sku['ID'] . "\">" . $sku['Descritor4'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>                                

                                <?php
                                if (!empty($dadosProduto['TipoDescritor5']))
                                {
                                ?>
                                    <div class="form-group">
                                        <label><?= $dadosProduto['TipoDescritor5']['Descricao'] ?></label></br>
                                        <select type="number" class="form-control" min="0" class="input-lg" name="">
                                            <?php
                                            foreach ((array) $dadosProduto['Skus'] as $sku)
                                            {
                                                echo "<option value=\"" . $sku['ID'] . "\">" . $sku['Descritor5'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>
                                
                            </form>	
                        </div>
                        <button>Adicionar no carrinho</button>
                    </div>

                    <div class="produto-descricao-parcela">
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li><span>2x sem juros de </span>R$ 1.278,00</li>
                                    <li><span>3x sem juros de </span>R$ 852,00 </li>
                                    <li><span>4x sem juros de </span>R$ 639,00</li>
                                    <li><span>5x sem juros de </span>R$ 511,20 </li>
                                    <li><span>6x sem juros de </span>R$ 426,00 </li>
                                    <li><span>7x sem juros de </span>R$ 365,00</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><span>8x sem juros de </span>R$ 319,50 </li>
                                    <li><span>9x sem juros de </span>R$ 284,00 </li>
                                    <li><span>10x sem juros de </span>R$ 255,60 </li> 
                                    <li><span>11x sem juros de </span>R$ 225,60 </li> 
                                    <li><span>12x sem juros de </span>R$ 155,60 </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="produto-descricao-dimensoes">
                        Tamanho: <span>6’ 3</span> | Largura: <span>20 3/8”</span> | Espessura: <span>2 3/16</span> | Vol: <span>28Lt</span>
                    </div>
                    <div class="produto-descricao-atributo">
                        <ul>
                            <li>Rabeta: <span>Round Square</span></li>
                            <li>Sistema de quilhas: <span>FCS 2 3fin setup</span></li>
                            <li>Construção: <span>PU Stringer</span></li>
                            <li>Tamanho de onda: <span>0 - 1,5metros</span></li>
                            <li>Nível do surfista: <span>Iniciante, Intermediário e Avançado</span></li>
                            <li>Tipo de onda: <span>Reef break, Point break e Beach break</span></li>
                        </ul>
                    </div>
                    <div class="produto-social">
                        COMPARTILHE: 
                        <a href=""><i class="produto-social-icon icon-facebook">&#xe800;</i></a>
                        <a href=""><i class="produto-social-icon icon-instagram">&#xe803;</i></a>
                        <a href=""><i class="produto-social-icon icon-youtube">&#xe804;</i></a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

            <section class="produto-mais-info hidden-xs">
                <div class="container">
                    <h4>Mais Informações</h4>
                    <div class="produto-mais-info-scroll">
                        <h5>Channel Islands</h5>
                        <p>
                            Since 1969, Channel Islands Surfboards has been dedicated to performance and quality through hard work, innovation, and originality. Over the last 43 years, Channel Islands has grown from a local grass-roots operation to a cutting edge organization, catering to the best surfers in the world. It started with hard-core surfing and quality in mind and these guidelines have brought us through four decades of constant change in the surf industry. Channel Islands will shape the new millennium with innovative design and quality as our main focus.
                        </p>
                        <p>
                            “The driving force behind Channel Islands Surfboards is the demand on design created by the world’s greatest surfers, allowing for the highest performance surfing possible. To provide the most dedicated surfers with the most advanced, performance designs is my passion” – Al Merrick, Designer/Shaper
                        </p>
                        <p>
                            CI is a privately held organization focused on rider-driven product and manufacturing the best possible equipment available. Located in a state-of-the-art facility just blocks from Rincon Del Mar, the CI HQ represents a foundation for developing, testing, and building boards while providing jobs in Santa Barbara for many years to come.
                        </p>
                        <p>
                            Channel Islands Surfboards was created by Al and Terry Merrick in 1969. From his birth, Britt spent his days in the factory by the beach in Santa Barbara, from toddling around blanks to sweeping out shaping rooms. Eventually he started shaping alongside his father Al in 1990. He is now the lead shaper and designer for CI and carries on the family tradition of developing high performance board designs collaborating with the world’s greatest surfers. From Tom Curren, to Dane Reynolds, Channel Islands continues to evolve with the highest standard of surfing.
                        </p>
                    </div>
                </div>
            </section>

            <section class="produto-relacionado hidden-xs">	  	
                <h3>Produtos Relacionados</h3>
                <div id="mais-vendidos-carousel" class="container-carousel carousel slide" data-ride="carousel">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <div class="row">
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-sale">
                                        <img src="/images/produtos/mais-bike.png" />
                                        <span>Jaqueta Kuza Unisex</span>
                                        <span>Patagonia</span>
                                        <span>R$ 498,30</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-new">
                                        <img src="/images/produtos/mais-bike.png" />
                                        <span>Relógio Panda</span>
                                        <span>Suunto</span>
                                        <span>R$ 1.098,30</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-new">
                                        <img src="/images/produtos/mais-bike.png" />
                                        <span>Sumperjumper29” feminino</span>
                                        <span>Specialized</span>
                                        <span>R$ 4.498,30</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-off">
                                        <img src="/images/produtos/mais-palheta.png" />
                                        <span>Deck Parker Coffin</span>
                                        <span>Channel Islands</span>
                                        <span>R$ 198,00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row">
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-off">
                                        <img src="/images/produtos/mais-palheta.png" />
                                        <span>Jaqueta Kuza Unisex</span>
                                        <span>Patagonia</span>
                                        <span>R$ 498,30</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label">
                                        <img src="/images/produtos/mais-palheta.png" />
                                        <span>Relógio Panda</span>
                                        <span>Suunto</span>
                                        <span>R$ 1.098,30</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-new">
                                        <img src="/images/produtos/mais-bike.png" />
                                        <span>Sumperjumper29” feminino</span>
                                        <span>Specialized</span>
                                        <span>R$ 4.498,30</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-thumb">
                                    <div class="p-label-sale">
                                        <img src="/images/produtos/mais-palheta.png" />
                                        <span>Deck Parker Coffin</span>
                                        <span>Channel Islands</span>
                                        <span>R$ 198,00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="mais-vendidos-carousel-controle">
                        <a class="pull-left" href="#mais-vendidos-carousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
                        </a>
                        <a class="pull-right" href="#mais-vendidos-carousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>

            </section>		