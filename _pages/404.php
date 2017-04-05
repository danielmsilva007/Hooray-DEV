<?php
if (!defined('HoorayWeb')) 
{
    die;
}
?>

<section class="conteudo">
    <div class="text-center">
        <h2>Oops... 404.</h2>
        <p>Você procura por algum produto?</p>
        <p>Use o campo de busca abaixo ou retorne para uma de nossas categorias.</P>
    </div>
</section>

<!-- <div class="make-space-bet"></div> -->

<section class="descubra">
    <form class="form-inline" name="busca404" method="get" action="/busca">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> </button>
            </div>
            <input class="form-control" type="text" name="termobusca" placeholder="O que você procura?" />
        </div>
    </form>	
</section>

<div class="make-space-bet"></div>