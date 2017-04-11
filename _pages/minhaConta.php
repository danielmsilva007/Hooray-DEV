<?php
if (!defined('HoorayWeb')) 
{
    die;
}
?>

<?php
if (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) //usuário logado
{
    if (strlen($dadosLogin['Parceiro']['CNPJ']) == 11) //CPF
    {
        $cnpj = mascara($dadosLogin['Parceiro']['CNPJ'],"###.###.###-##");
    }
    else //CNPJ
    {
        $cnpj = mascara($dadosLogin['Parceiro']['CNPJ'],"##.###.###/####-##");
    }

    $enderecos = getRest(str_replace("{IDParceiro}", $dadosLogin['Parceiro']['ID'], $endPoint['endcadastral']));

    foreach ((array) $enderecos['Enderecos'] as $endereco)
    {
        switch ($endereco['Tipo'])
        {
            case 0 :
                $enderecoPrincipal = $endereco;
                break;

            case 1 :
                $enderecoEntrega = $endereco;
                break;

            case 2 :
                $enderecoCobranca = $endereco;
                break;        
        }
    }

    ?>
    
    <script type="text/javascript">
        function alterarCadastroBasico()
        {
            $('#retornoCadastro').html('Validando cadastro...');

            var cadNome = $('#cadNome').val();
            var cadDtNascimento = $('#cadDtNascimento').val();
            var cadTelefone = $('#cadTelefone').val();

            $.post('/_pages/atualizarCadastro.php', {postloginid:'<?= $dadosLogin['ID'] ?>',
                                                    postnome:cadNome,
                                                    postnascimento:cadDtNascimento,
                                                    posttelefone:cadTelefone,
                                                    postenviarcadbasico:'<?= md5("cadastro") ?>'},
            function(dataCadastroBasico)
            {
                if (dataCadastroBasico.substring(0,2) == "!!")
                {
                    $('#retornoCadastro').html(dataCadastroBasico.substring(2));
                }
                else
                {
                    $('#retornoCadastro').html(dataCadastroBasico);
                    
                    document.cadFormBasico.onsubmit = true;
                    document.cadFormBasico.submit();
                }
            });

            return false;
        }    
    
        function trocarSenha()
        {
            $('#retornoSenha').html('Alterando senha...');

            var altSenhaAtual = $('#altSenhaAtual').val();
            var altNovaSenha = $('#altNovaSenha').val();
            var altConfNovaSenha = $('#altConfNovaSenha').val();

            $.post('/_pages/atualizarCadastro.php', {postloginid:'<?= $dadosLogin['ID'] ?>',
                                                    postsenhaatual:altSenhaAtual,
                                                    postnovasenha:altNovaSenha,
                                                    postconfnovasenha:altConfNovaSenha,
                                                    postalterarsenha:'<?= md5("alterarSenha") ?>'},
            function(dataAltSenha)
            {
                $('#retornoSenha').html(dataAltSenha);
            });            
        
            document.altSenha.altSenhaAtual.value = '';
            document.altSenha.altNovaSenha.value = '';
            document.altSenha.altConfNovaSenha.value = '';
        }
        
        function obterEndereco(tipoEndereco)
        {
            var endCEP;
            
            if (tipoEndereco == 'principal')
            {
                $('#resultCEPPrincipal').html('');
                document.endPrinForm.endPrinLogradouro.value = 'Buscando endereço...';
                endCEP = $('#endPrinCEP').val();
            }
            else
            {
                $('#resultCEPEntrega').html('');
                document.endEntForm.endEntLogradouro.value = 'Buscando endereço...';
                endCEP = $('#endEntCEP').val();
            }
            
            $.post('/_pages/atualizarCadastro.php', {postcep:endCEP,
                                                    postbuscarcep:'<?= md5 ("buscarCep") ?>'},
            function(data)
            {
                if (data.substring(0,2) == "!!")
                {
                    if (tipoEndereco == 'principal')
                    {
                        $('#resultCEPPrincipal').html(data.substring(2));
                        document.endPrinForm.endPrinLogradouro.value = '';
                        document.endPrinForm.endPrinCidade.value = '';
                        document.endPrinForm.endPrinUF.value = '';                        
                    }    
                    else
                    {
                        $('#resultCEPEntrega').html(data.substring(2));
                        document.endEntForm.endEntLogradouro.value = '';
                        document.endEntForm.endEntCidade.value = '';
                        document.endEntForm.endEntUF.value = '';                        
                    }
                }
                else
                {
                    if (tipoEndereco == 'principal')
                    {
                        var dadosEndereco = data.split("#");
                        document.endPrinForm.endPrinLogradouro.value = dadosEndereco[1];
                        document.endPrinForm.endPrinCidade.value = dadosEndereco[2];
                        document.endPrinForm.endPrinUF.value = dadosEndereco[3];
                    }
                    else
                    {
                        var dadosEndereco = data.split("#");
                        document.endEntForm.endEntLogradouro.value = dadosEndereco[1];
                        document.endEntForm.endEntCidade.value = dadosEndereco[2];
                        document.endEntForm.endEntUF.value = dadosEndereco[3];
                    }
                }
            });
        }
        
        function alterarEndereco(tipoEndereco)
        {
            if (tipoEndereco == 'principal')
            {
                $('#retornoEnderecoPrincipal').html('Validando endereço...');
            }
            else
            {
                $('#retornoEnderecoEntrega').html('Validando endereço...');
            }
            
            var endDestinatario;
            var endCEP;
            var endLogradouro;
            var endNumero;
            var endComplemento;
            var endBairro;
            var endCidade;
            var endUF;
            var endID;

            if (tipoEndereco == 'principal')
            {
                endDestinatario = $('#endPrinDestinatario').val();
                endCEP = $('#endPrinCEP').val();
                endLogradouro = $('#endPrinLogradouro').val();
                endNumero = $('#endPrinNumero').val();
                endComplemento = $('#endPrinComplemento').val();
                endBairro = $('#endPrinBairro').val();
                endCidade = $('#endPrinCidade').val();
                endUF = $('#endPrinUF').val();
                endID = '<?= (!empty($enderecoPrincipal['ID'])) ? $enderecoPrincipal['ID'] : "" ?>';
            }
            else
            {
                endDestinatario = $('#endEntDestinatario').val();
                endCEP = $('#endEntCEP').val();
                endLogradouro = $('#endEntLogradouro').val();
                endNumero = $('#endEntNumero').val();
                endComplemento = $('#endEntComplemento').val();
                endBairro = $('#endEntBairro').val();
                endCidade = $('#endEntCidade').val();
                endUF = $('#endEntUF').val();
                endID = '<?= (!empty($enderecoEntrega['ID'])) ? $enderecoEntrega['ID'] : "" ?>';
            }

            $.post('/_pages/atualizarCadastro.php', {postdestinatario:endDestinatario,
                                                    postcep:endCEP,
                                                    postlogradouro:endLogradouro,
                                                    postnumero:endNumero,
                                                    postcomplemento:endComplemento,
                                                    postbairro:endBairro,
                                                    postcidade:endCidade,
                                                    postuf:endUF,
                                                    postidendereco:endID,
                                                    postidparceiro:'<?= $dadosLogin['Parceiro']['ID'] ?>',
                                                    postenviarendereco:'<?= md5("alterarEndereco") ?>',
                                                    posttipoendereco:tipoEndereco},
            function(dataCadastroEndereco)
            {
                if (dataCadastroEndereco.substring(0,2) == "!!")
                {
                    if (tipoEndereco == 'principal')
                    {
                        $('#retornoEnderecoPrincipal').html(dataCadastroEndereco.substring(2));
                    }
                    else
                    {
                        $('#retornoEnderecoEntrega').html(dataCadastroEndereco.substring(2));
                    }
                }
                else
                {
                    if (tipoEndereco == 'principal')
                    {
                        $('#retornoEnderecoPrincipal').html(dataCadastroEndereco);
                        document.endPrinForm.onsubmit = true;
                        document.endPrinForm.submit();
                    }
                    else
                    {
                        $('#retornoEnderecoEntrega').html(dataCadastroEndereco);
                        document.endEntForm.onsubmit = true;
                        document.endEntForm.submit();                        
                    }
                }
            });

            return false;        
        }
        
        function apagarEndereco(idEndereco)
        {
            $('#retornoApagarEndereco').html('Excluindo endereço...');
            
            $.post('/_pages/atualizarCadastro.php', {postidendereco:idEndereco,
                                                    postapagarendereco:'<?= md5("excluirEndereco") ?>'},
            function(dataDeleteEndereco)
            {
                if (dataDeleteEndereco.substring(0,2) == "!!")
                {
                    $('#retornoApagarEndereco').html(dataDeleteEndereco.substring(2));
                }
                else
                {
                    $('#retornoApagarEndereco').html(dataDeleteEndereco);
                    document.excluirEnd.onsubmit = true;
                    document.excluirEnd.submit();                       
                }
            });
            
            return false;
        }
        
        function delWishList(idProduto, idDiv)
        {
            $.post('/_pages/atualizarCadastro.php', {postidlogin:'<?= (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) ? $dadosLogin['ID'] : "-1" ?>',
                                                     postidproduto:idProduto,
                                                     postwishlist:'<?= md5("remover") ?>'},
            function(dataWishList)
            {
                $('#' + idDiv).html(dataWishList);
            });
        }
    </script>

    <section class="conta">
        <h4>Minha conta</h4>
        <div class="row">
            <div class="col-md-3">
                <form name="formSair" id="formSair" method="post" action="/">
                    <ul class="nav tabs-left">	  					
                        <li class="active"><a href="#inf-conta" data-toggle="tab">Informações da conta</a></li>
                        <li><a href="#meus-end" data-toggle="tab">Meus endereços</a></li>
                        <li><a href="#meus-ped" data-toggle="tab">Meus pedidos</a></li>
                        <li><a href="/contato?tipo=trocasdevolucoes">Trocas e devoluções</a></li>
                        <li><a href="#meus-fav" data-toggle="tab">Wishlist</a></li>
                        <li><a href="javascript:document.formSair.submit();" style="color:red;">Sair</a></li>
                    </ul>
                    <input type="hidden" name="logoff" value="<?= md5("logoff") ?>">
                </form>
            </div>

            <div class="tab-content col-md-9">

                <!-- pane infos -->
                <div class="tab-pane active" id="inf-conta">
                    <div class="conta-painel">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Nome</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><?= $dadosLogin['Parceiro']['RazaoSocial'] ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Data de nascimento</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><?= date_format(date_create($dadosLogin['Parceiro']['DataNascimento']), "d/m/Y") ?></p>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-sm-3">Telefone</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><?= (!empty($dadosLogin['Parceiro']['DDDTelefone'])) ? "(" . substr($dadosLogin['Parceiro']['DDDTelefone'], -2) . ") " : "" ?><?= $dadosLogin['Parceiro']['Telefone'] ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">E-mail</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><?= $dadosLogin['Parceiro']['Email'] ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Sexo</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><?= $sexo[$dadosLogin['Parceiro']['Sexo']] ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3"><?= ($dadosLogin['Parceiro']['Sexo'] == 2) ? "CNPJ" : "CPF" ?></label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><?= $cnpj ?></p>
                            </div>
                        </div>
                        <button href="#inf-conta-edit" data-toggle="tab" class="btn btn-default">Editar informações</button>
                        <button href="#inf-conta-edit-pass" data-toggle="tab" class="btn btn-default">Alterar senha</button>
                    </div>
                </div>
                <!-- fim pane -->            

                <!-- pane edit conta info -->
                <div class="tab-pane" id="inf-conta-edit">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form name="cadFormBasico" method="post" action="/minhaconta" onsubmit="return alterarCadastroBasico()">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nome</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadNome" id="cadNome" class="form-control" value="<?= $dadosLogin['Parceiro']['RazaoSocial'] ?>" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Data de nascimento</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadDtNascimento" id="cadDtNascimento" class="form-control" placeholder="DD/MM/AAAA" maxlength="10" value="<?= date_format(date_create($dadosLogin['Parceiro']['DataNascimento']), "d/m/Y") ?>" required="required" />
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="control-label col-sm-3">Telefone</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadTelefone" id="cadTelefone" class="form-control" placeholder="(00) 0000-0000" value="<?= (!empty($dadosLogin['Parceiro']['DDDTelefone'])) ? "(" . substr($dadosLogin['Parceiro']['DDDTelefone'], -2) . ") " : "" ?><?= $dadosLogin['Parceiro']['Telefone'] ?>" maxlength="15" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">E-mail</label>
                                <div class="col-sm-9 col-md-6">
                                    <?= $dadosLogin['Parceiro']['Email'] ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Sexo</label>
                                <div class="col-sm-9 col-md-6">
                                    <?= $sexo[$dadosLogin['Parceiro']['Sexo']] ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3"><?= ($dadosLogin['Parceiro']['Sexo'] == 2) ? "CNPJ" : "CPF" ?></label>
                                <div class="col-sm-9 col-md-6">
                                    <?= $cnpj ?>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-12" id="retornoCadastro"></div>
                            
                            <button href="#inf-conta" data-toggle="tab" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <input type="hidden" name="cadastroBasico" value="<?= md5("editarcadastro") ?>">
                        </form>
                    </div>
                </div>
                <!-- fim pane -->
                
                <!-- pane meus enderecos -->
                <div class="tab-pane" id="meus-end">
                    <div class="conta-painel">
                        <div class="panel conta-painel">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label class="control-label">Principal</label>
                                        <p class="form-control-static">
                                            <?php
                                            if (!empty($enderecoPrincipal))
                                            {
                                                echo $enderecoPrincipal['Destinatario'] ."<br/>";
                                                echo htmlentities($enderecoPrincipal['Logradouro']) . ", " . $enderecoPrincipal['Numero'];
                                                echo (!empty($enderecoPrincipal['Complemento'])) ? " - " . htmlentities($enderecoPrincipal['Complemento']) . "<br/>" : "<br/>";
                                                echo mascara($enderecoPrincipal['CEP'], "#####-###") . " - " . htmlentities($enderecoPrincipal['Bairro']) . "<br/>";
                                                echo htmlentities($enderecoPrincipal['Cidade']['Nome']) . " - " . htmlentities($enderecoPrincipal['Cidade']['Estado']['Sigla']);
                                            }
                                            else
                                            {
                                                echo "Você não possui endereços cadatrados.<br> Clique em editar para adicionar.";
                                            }
                                            
                                            ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Entrega</label>
                                        <p class="form-control-static">
                                            <?php
                                            if (!empty($enderecoEntrega))
                                            {
                                                echo $enderecoEntrega['Destinatario'] ."<br/>";
                                                echo htmlentities($enderecoEntrega['Logradouro']) . ", " . $enderecoEntrega['Numero'];
                                                echo (!empty($enderecoEntrega['Complemento'])) ? " - " . htmlentities($enderecoEntrega['Complemento']) . "<br/>" : "<br/>";
                                                echo mascara($enderecoEntrega['CEP'], "#####-###") . " - " . htmlentities($enderecoEntrega['Bairro']) . "<br/>";
                                                echo htmlentities($enderecoEntrega['Cidade']['Nome']) . " - " . htmlentities($enderecoEntrega['Cidade']['Estado']['Sigla']);
                                            }
                                            else
                                            {
                                                echo "Seu endereço de entrega é igual ao seu endereço principal.<br> Clique em editar para alterá-lo.";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group conta-painel-enderecos">
                                    <div class="col-md-6">
                                        <div class="col-md-2"><a href="#meus-end-edit-principal" data-toggle="tab"><span class="glyphicon glyphicon-edit"></span> Editar</a></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-2"><a href="#meus-end-edit-entrega" data-toggle="tab"><span class="glyphicon glyphicon-edit"></span> Editar</a></div>
                                        <?php
                                        if (!empty($enderecoEntrega))
                                        {
                                            echo "<div class=\"col-md-2\"><a href=\"#meus-end-delete\" data-toggle=\"tab\"><span class=\"glyphicon glyphicon-remove\"></span> Excluir</a></div>";
                                        }
                                        ?>
                                    </div>                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- fim pane -->

                <!-- pane meus enderecos edit principal -->
                <div class="tab-pane" id="meus-end-edit-principal">
                    <?php
                    $cepConsulta = (!empty($enderecoPrincipal['CEP'])) ? $enderecoPrincipal['CEP'] : "0";
                    
                    $enderecoPorCEP = getRest(str_replace("{CEP}", $cepConsulta, $endPoint['enderecoporcep']));
                    ?>
                    <div class="conta-painel">
                        <form name="endPrinForm" id="endPrinForm" method="post" action="/minhaconta" onsubmit="return alterarEndereco('principal')">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Tipo do endereço</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinDescricaoTipo" id="endPrinDescricaoTipo" value="Principal" disabled="disabled" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Destinatário</label>
                                <div class="col-sm-9 col-md-6">
                                    <input name="endPrinDestinatario" id="endPrinDestinatario" type="text" class="form-control" value="<?= (!empty($enderecoPrincipal['Destinatario'])) ? $enderecoPrincipal['Destinatario'] : "" ?>" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">CEP</label>
                                <div class="col-sm-9 col-md-6">
                                    <div class="input-group">
                                        <input name="endPrinCEP" id="endPrinCEP" type="text" value="<?= (!empty($enderecoPrincipal['CEP'])) ? mascara($enderecoPrincipal['CEP'], "#####-###") : "" ?>" class="form-control" required="required"/>
                                        <span class="input-group-addon"><a href="javascript:obterEndereco('principal')">Buscar endereço</a></span>
                                    </div>
                                    <span id="resultCEPPrincipal"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Logradouro</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinLogradouro" id="endPrinLogradouro" value="<?= (!empty($enderecoPrincipal['Logradouro'])) ?  $enderecoPrincipal['Logradouro'] : "" ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Número</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinNumero" id="endPrinNumero" value="<?= (!empty($enderecoPrincipal['Numero'])) ? $enderecoPrincipal['Numero'] : "" ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Complemento</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinComplemento" id="endPrinComplemento" value="<?= (!empty($enderecoPrincipal['Complemento'])) ? $enderecoPrincipal['Complemento'] : "" ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Bairro</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinBairro" id="endPrinBairro" value="<?= (!empty($enderecoPrincipal['Bairro'])) ? $enderecoPrincipal['Bairro'] : "" ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Cidade</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinCidade" id="endPrinCidade" value="<?= (!empty($enderecoPorCEP['Cidade']['Nome'])) ? $enderecoPorCEP['Cidade']['Nome'] : "" ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Estado</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinUF" id="endPrinUF" value="<?= (!empty($enderecoPorCEP['Cidade']['Estado']['Sigla'])) ? $enderecoPorCEP['Cidade']['Estado']['Sigla'] : "" ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            
                            <div id="retornoEnderecoPrincipal" class="col-sm-12 col-md-12"></div>
                                
                            <button href="#meus-end" data-toggle="tab" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->

                <!-- pane meus enderecos edit entrega -->
                <div class="tab-pane" id="meus-end-edit-entrega">
                    <?php
                    $cepConsulta = (!empty($enderecoEntrega['CEP'])) ? $enderecoEntrega['CEP'] : "0";
                    
                    $enderecoPorCEP = getRest(str_replace("{CEP}", $cepConsulta, $endPoint['enderecoporcep']));
                    ?>
                    <div class="conta-painel">
                        <form name="endEntForm" id="endEntForm" method="post" action="/minhaconta" onsubmit="return alterarEndereco('entrega')">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Tipo do endereço</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntDescricaoTipo" id="endEntDescricaoTipo" value="Entrega" disabled="disabled" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Destinatário</label>
                                <div class="col-sm-9 col-md-6">
                                    <input name="endEntDestinatario" id="endEntDestinatario" type="text" class="form-control" value="<?= (!empty($enderecoEntrega['Destinatario'])) ? $enderecoEntrega['Destinatario'] : "" ?>" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">CEP</label>
                                <div class="col-sm-9 col-md-6">
                                    <div class="input-group">
                                        <input name="endEntCEP" id="endEntCEP" type="text" value="<?= (!empty($enderecoEntrega['CEP'])) ? mascara($enderecoEntrega['CEP'], "#####-###") : "" ?>" class="form-control" required="required"/>
                                        <span class="input-group-addon"><a href="javascript:obterEndereco('entrega')">Buscar endereço</a></span>
                                    </div>
                                    <span id="resultCEPEntrega"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Logradouro</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntLogradouro" id="endEntLogradouro" value="<?= (!empty($enderecoEntrega['Logradouro'])) ?  $enderecoEntrega['Logradouro'] : "" ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Número</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntNumero" id="endEntNumero" value="<?= (!empty($enderecoEntrega['Numero'])) ? $enderecoEntrega['Numero'] : "" ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Complemento</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntComplemento" id="endEntComplemento" value="<?= (!empty($enderecoEntrega['Complemento'])) ? $enderecoEntrega['Complemento'] : "" ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Bairro</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntBairro" id="endEntBairro" value="<?= (!empty($enderecoEntrega['Bairro'])) ? $enderecoEntrega['Bairro'] : "" ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Cidade</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntCidade" id="endEntCidade" value="<?= (!empty($enderecoPorCEP['Cidade']['Nome'])) ? $enderecoPorCEP['Cidade']['Nome'] : "" ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Estado</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endEntUF" id="endEntUF" value="<?= (!empty($enderecoPorCEP['Cidade']['Estado']['Sigla'])) ? $enderecoPorCEP['Cidade']['Estado']['Sigla'] : "" ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            
                            <div id="retornoEnderecoEntrega" class="col-sm-12 col-md-12"></div>
                                
                            <button href="#meus-end" data-toggle="tab" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->                
                
                <!-- pane meus enderecos delete -->
                <div class="tab-pane" id="meus-end-delete">
                    <div class="conta-painel">
                        <div class="panel conta-painel">
                            <form name="excluirEnd" id="excluirEnd" class="form-horizontal" method="post" action="/minhaconta" onsubmit="return apagarEndereco('<?= $enderecoEntrega['ID'] ?>')">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label">Confirma a exclusão do endereço abaixo?</label>
                                        <p class="form-control-static">
                                            <?php
                                            if (!empty($enderecoEntrega))
                                            {
                                                echo $enderecoEntrega['Destinatario'] ."<br/>";
                                                echo htmlentities($enderecoEntrega['Logradouro']) . ", " . $enderecoEntrega['Numero'];
                                                echo (!empty($enderecoEntrega['Complemento'])) ? " - " . htmlentities($enderecoEntrega['Complemento']) . "<br/>" : "<br/>";
                                                echo mascara($enderecoEntrega['CEP'], "#####-###") . " - " . htmlentities($enderecoEntrega['Bairro']) . "<br/>";
                                                echo htmlentities($enderecoEntrega['Cidade']['Nome']) . " - " . htmlentities($enderecoEntrega['Cidade']['Estado']['Sigla']);
                                            }
                                            else
                                            {
                                                echo "Você não possui endereço de entrega cadastrado.";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div id="retornoApagarEndereco" class="col-sm-12 col-md-12"></div>

                                <button href="#meus-end" data-toggle="tab" class="btn btn-default">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Excluir</button>                                   
                            </form>
                        </div>
                    </div>
                </div>
                <!-- fim pane -->                  

                <!-- pane meus pedidos -->
                <div class="tab-pane" id="meus-ped">

                    <?php
                    $meusPedidos = getRest(str_replace("{IDParceiro}", $dadosLogin['Parceiro']['ID'], $endPoint['meuspedidos']));
                    
                    foreach ((array) $meusPedidos as $pedido)
                    {
                        $itensPedido = getRest(str_replace("{IDPedido}", $pedido['ID'], $endPoint['meuspedidosdet']));
                    ?>
                        <div class="conta-painel">
                            <div class="row">
                                <div class="col-md-3">
                                    Pedido: <?= $pedido['Numero'] ?>
                                </div>
                                <div class="col-md-3">
                                    Data: <?= date_format(date_create($pedido['DataVenda']), "d/m/Y") ?>
                                </div>
                                <div class="col-md-3">
                                    Valor pedido: <?= formatar_moeda($pedido['ValorTotal']) ?>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a href="#meus-ped-hide-<?= $pedido['Numero'] ?>" class="conta-painel-toggle" data-toggle="collapse">
                                        <?= count($itensPedido[0]['PedidoItens']) ?> <?= (count($itensPedido[0]['PedidoItens']) > 1) ? "Itens" : "Item" ?>
                                    </a>
                                </div>
                            </div>
                            <div class="well">
                                <div id="meus-ped-hide-<?= $pedido['Numero'] ?>" class="collapse">
                                    <?php
                                    $itens = [];
                                    foreach ((array) $itensPedido[0]['PedidoItens'] as $itemPedido)
                                    {
                                        if (!array_key_exists($itemPedido['Fornecedor'], $itens))
                                        {
                                            $itens[$itemPedido['Fornecedor']] = ["Fornecedor" => $itemPedido['Fornecedor'],
                                                                                 "Status" => $itemPedido['StatusMarketPlace'],
                                                                                 "Itens" => []
                                                ];
                                        }                                        
                                        
                                        array_push($itens[$itemPedido['Fornecedor']]['Itens'], $itemPedido);
                                    }
                                    
                                    $i = 1;
                                    foreach ((array) $itens as $item)
                                    {
                                        if ($i != 1) echo "<div class=\"line-bet\"></div>";
                                    ?>
                                        <div class="conta-painel-entrega">Entrega <?= $i ?> de <?= count($itens) ?> - Entregue por <span><?= $item['Fornecedor'] ?></span></div>
                                        <div class="conta-checkpoint">
                                            <div class="row">
                                                <div class="linha">
                                                    <div class="col-xs-1"></div>
                                                    <div class="col-xs-2 first<?= ($item['Status'] == 1) ? " active" : "" ?><?= ($item['Status'] > 1) ? " visited" : "" ?>">
                                                        <div class="circulo"></div>
                                                        <span>Pedido</span>
                                                    </div>
                                                    <div class="col-xs-2<?= ($item['Status'] == 2) ? " active" : "" ?><?= ($item['Status'] > 2) ? " visited" : "" ?>">
                                                        <div class="circulo"></div>
                                                        <span>Pagamento</span>
                                                    </div>
                                                    <div class="col-xs-2<?= ($item['Status'] == 3) ? " active" : "" ?><?= ($item['Status'] > 3) ? " visited" : "" ?>">
                                                        <div class="circulo"></div>
                                                        <span>Separação</span>
                                                    </div>
                                                    <div class="col-xs-2<?= ($item['Status'] == 4) ? " active" : "" ?><?= ($item['Status'] > 4) ? " visited" : "" ?>">
                                                        <div class="circulo"></div>
                                                        <span>Transporte</span>
                                                    </div>
                                                    <div class="col-xs-2 last<?= ($item['Status'] == 5) ? " active" : "" ?><?= ($item['Status'] > 5) ? " visited" : "" ?>">
                                                        <div class="circulo"></div>
                                                        <span>Entrega</span>
                                                    </div>
                                                    <div class="col-xs-1"></div>
                                                </div>
                                            </div>
                                        </div>                                        
                                    <?php
                                        foreach ((array) $item['Itens'] as $produto)
                                        {
                                        ?>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <?= $produto['NomeProduto'] ?>
                                                </div>
                                                <div class="col-md-2">
                                                    Quantidade: <?= $produto['Quantidade'] ?>
                                                </div>
                                                <div class="col-md-2 text-right">
                                                    <?= formatar_moeda($produto['ValorTotal']) ?>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- fim pane -->

                <!-- pane edit conta info senha -->
                <div class="tab-pane" id="inf-conta-edit-pass">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form name="altSenha" method="post" onsubmit="false">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Senha atual</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="altSenhaAtual" id="altSenhaAtual" class="form-control" placeholder="Senha atual" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nova senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="altNovaSenha" id="altNovaSenha" class="form-control" placeholder="Nova senha" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Repita a senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="altConfNovaSenha" id="altConfNovaSenha" class="form-control" placeholder="Repita a senha" required="required" />
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-12" id="retornoSenha"></div>
                            
                            <button href="#inf-conta" data-toggle="tab" class="btn btn-default">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="trocarSenha()">Alterar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->

                <!-- pane favoritos -->
                <div class="tab-pane" id="meus-fav">
                    <div class="conta-painel">
                        <?php
                        $itensWL = getRest(str_replace("{IDLogin}" , $dadosLogin['ID'], $endPoint['obterwishlist']));
                        
                        if (!empty($itensWL['Produtos']))
                        {
                            $i = 0;
                            foreach ((array) $itensWL['Produtos'] as $produtoWL)
                            {
                                if ($i != 0) echo "<div class=\"line-bet\"></div>";
                            ?>
                                <div class="row" id="retornoWishlist<?= $i ?>">
                                    <div class="col-md-2">
                                        <a href="/produto?id=<?= $produtoWL['ID'] ?>">
                                            <img src="<?= $produtoWL['ImagemMobile'] ?>" title="<?= $produtoWL['Descricao'] ?>"/>
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <a href="/produto?id=<?= $produtoWL['ID'] ?>">
                                            <?= $produtoWL['Descricao'] ?><br>
                                            <?= $produtoWL['Marca']['Descricao'] ?><br>
                                        </a>
                                        <a href="javascript:delWishList('<?= $produtoWL['ID'] ?>', 'retornoWishlist<?= $i ?>')" class="glyphicon glyphicon-trash" title="Remover o produto da sua Wishlist"><span class="text-btn"> Excluir</span></a>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <?= (!empty($produtoWL['PrecoDePor'])) ? "<s>" . formatar_moeda($produtoWL['PrecoDePor']['PrecoDe']) . "</s><br>" : "" ?>
                                        <?= formatar_moeda($produtoWL['PrecoVigente']) ?>
                                    </div>
                                </div>
                            <?php
                            $i ++;
                            }
                        }
                        else
                        {
                        ?>
                            <div class="panel conta-painel">
                                <div class="col-md-12">
                                    Sua Wish List ainda não possui nenhum produto.
                                </div>                        
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- fim pane -->

            </div>
        </div>
        
        <script>
            jQuery(function($){
                $("#cadDtNascimento").mask("99/99/9999");
                $("#cadTelefone").mask("?(99) 999999999");
            });
        </script>   
    </section>	

<?php
} 
else //não logado ou login expirado
{
?>  
    <script type="text/javascript">
        function obterBearerMC()
        {
            $('#resultBearerMC').html('Autenticando...');

            var loginEmailMC = $('#loginEmailMC').val();
            var loginSenhaMC = $('#loginSenhaMC').val();

            $.post('/_pages/login.php', {postlogin:loginEmailMC,postsenha:loginSenhaMC},
            function(data)
            {
                if (data.substring(0,2) == "!!")
                {
                    $('#resultBearerMC').html(data.substring(2));

                    document.loginFormMC.loginEmailMC.value = '';
                    document.loginFormMC.loginSenhaMC.value = '';
                }
                else
                {
                    $('#resultBearerMC').html(data);

                    document.autForm.submit();
                }
            });
            return false;
        }
        
        function realizarCadastro()
        {
            $('#resultCadastroMC').html('Validando cadastro...');

            var cadNomeMC = $('#cadNomeMC').val();
            var cadEmailMC = $('#cadEmailMC').val();
            var cadTelefoneMC = $('#cadTelefoneMC').val();
            var cadDtNascimentoMC = $('#cadDtNascimentoMC').val();
            var cadSexoMC = $('#cadSexoMC').val();
            var cadCNPJMC = $('#cadCNPJMC').val();
            var cadSenhaMC = $('#cadSenhaMC').val();
            var cadConfSenhaMC = $('#cadConfSenhaMC').val();

            $.post('/_pages/atualizarCadastro.php', {postnome:cadNomeMC,
                                                    postemail:cadEmailMC,
                                                    posttelefone:cadTelefoneMC,
                                                    postnascimento:cadDtNascimentoMC,
                                                    postsexo:cadSexoMC,
                                                    postcnpj:cadCNPJMC,
                                                    postsenha:cadSenhaMC,
                                                    postconfsenha:cadConfSenhaMC,
                                                    postenviarcadcompleto:'<?= md5("cadastro") ?>'},
            function(dataCadastro)
            {
                if (dataCadastro.substring(0,2) == "!!")
                {
                    $('#resultCadastroMC').html(dataCadastro.substring(2));
                }
                else
                {
                    $('#resultCadastroMC').html(dataCadastro);

                    document.cadFormCompleto.reset();
                    document.cadFormCompleto.cadNomeMC.value = '';
                    document.cadFormCompleto.cadEmailMC.value = '';
                }
            });
            return false;
        }
        
        function recuperarSenhaMC()
        {
            $('#resultRecuperarSenhaMC').html('Solicitando nova senha...');

            var recEmailMC = $('#recEmailMC').val();
            
            $.post('/_pages/atualizarCadastro.php', {postemail:recEmailMC,
                                                     postrecsenha:'<?= md5("recuperarsenha") ?>'},
            function(dataSenha)
            {
                $('#resultRecuperarSenhaMC').html(dataSenha);
                document.getElementById("recEmailMC").value = "";
            });
        }            
    </script>

    <section class="conta">

        <h4>Faça login ou cadastre-se</h4>

        <div class="row">
            <div class="col-md-3">
                <ul class="nav tabs-left">	  					
                    <?php 
                    if (!empty($phpPost['cadastroResult']) && $phpPost['cadastroResult'] == md5("cadastro"))
                    {
                        $activeMenuLogin = "";
                        $activeDivLogin = "";
                        $activeMenuCadastro = " class=\"active\"";
                        $activeDivCadastro = " active";                        
                    }
                    else
                    {
                        $activeMenuLogin = " class=\"active\"";
                        $activeDivLogin = " active";
                        $activeMenuCadastro = "";
                        $activeDivCadastro = "";
                    }
                    
                    ?>  
                    <li<?= $activeMenuLogin ?>><a href="#inf-login" data-toggle="tab">Faça Login</a></li>
                    <li<?= $activeMenuCadastro ?>><a href="#inf-cadastro" data-toggle="tab">Cadastre-se</a></li>
                    <li><a href="#inf-senha" data-toggle="tab">Esqueci minha senha</a></li>
                </ul>
            </div>
            <div class="tab-content col-md-9">

                <!-- pane login -->
                <div class="tab-pane<?= $activeDivLogin ?>" id="inf-login">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form name="loginFormMC" id="loginFormMC" method="post" action="/" onSubmit="return obterBearerMC()">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">E-mail</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="email" name="loginEmailMC" id="loginEmailMC" class="form-control" placeholder="E-mail" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="loginSenhaMC" id="loginSenhaMC" class="form-control" placeholder="Senha" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3"></label>
                                <div class="col-sm-9 col-md-6" id="resultBearerMC"></div>
                            </div>                            
                            <button type="reset" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->                    

                <!-- pane cadastro -->
                <div class="tab-pane<?= $activeDivCadastro ?>" id="inf-cadastro">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form name="cadFormCompleto" method="post" action="/" onSubmit="false">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nome</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadNomeMC" id="cadNomeMC" class="form-control" placeholder="Nome" value="<?= (!empty($phpPost['cadNome'])) ? $phpPost['cadNome'] : "" ?>" required="required" />
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="control-label col-sm-3">E-mail</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="email" name="cadEmailMC" id="cadEmailMC" class="form-control" placeholder="E-mail" value="<?= (!empty($phpPost['cadEmail'])) ? $phpPost['cadEmail'] : "" ?>" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Telefone</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadTelefoneMC" id="cadTelefoneMC" class="form-control" placeholder="(00) 0000-0000" maxlength="15" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Data de nascimento</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadDtNascimentoMC" id="cadDtNascimentoMC" class="form-control" placeholder="DD/MM/AAAA" maxlength="10" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Sexo</label>
                                <div class="col-sm-9 col-md-6">
                                    <select name="cadSexoMC" id="cadSexoMC" class="form-control">
                                    <?php
                                    for ($i = 0; $i < count($sexo); $i++)
                                    {
                                        echo "<option value=\"" . $i . "\">" . $sexo[$i] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="control-label col-sm-3" id="cadLabelCNPJ">CPF/CNPJ</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadCNPJMC" id="cadCNPJMC" class="form-control" placeholder="CPF/CNPJ" required="required" />
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="cadSenhaMC" id="cadSenhaMC" class="form-control" placeholder="Senha" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Repita a senha</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="password" name="cadConfSenhaMC" id="cadConfSenhaMC" class="form-control" placeholder="Senha" required="required" />
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-12" id="resultCadastroMC"></div>

                            <button type="reset" class="btn btn-default">Cancelar</button>
                            <button type="button" onclick="realizarCadastro()" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->                    

                <!-- pane senha -->
                <div class="tab-pane" id="inf-senha">
                    <div class="conta-painel conta-painel-edit-infos">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">E-mail</label>
                            <div class="col-sm-9 col-md-6">
                                <input type="email" name="recEmailMC" id="recEmailMC" class="form-control" placeholder="Digite seu e-mail" required="required" />
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12" id="resultRecuperarSenhaMC"></div>

                        <button type="reset" class="btn btn-default">Cancelar</button>
                        <button type="submit" onclick="recuperarSenhaMC()" class="btn btn-primary">Recuperar</button>
                    </div>
                </div>
                <!-- fim pane -->                    
                
            </div>
        </div>
        <script>
            jQuery(function($){
                $("#cadDtNascimentoMC").mask("99/99/9999");
                $("#cadTelefoneMC").mask("?(99) 999999999");
            });
        </script>   
    </section>	
    
<?php    
}
?>