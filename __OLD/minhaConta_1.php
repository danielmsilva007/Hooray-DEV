<?php
if (!defined('HoorayWeb')) 
{
    die;
}

$sexo = [0 => "Feminino", 
         1 => "Masculino",
         2 => "Empresa"
    ];

$tipoEndereco = [0 => "Principal",
                 1 => "Entrega",
    ];

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

    if (!empty($phpPost['cadSalvar']) && $phpPost['cadSalvar'] == md5("editarcadastro")) //editando cadastro
    {
        $telefone = preg_replace('/\D/', '', $phpPost['cadTelefone']);

        $DDDTelefone = substr($telefone, 0, 2);
        $numTelefone = substr($telefone, 2);

        $dataNascimento = preg_replace('/\D/', '', $phpPost['cadDtNascimento']);

        $diaNasc = substr($dataNascimento, 0, 2);
        $mesNasc = substr($dataNascimento, 2, 2);
        $anoNasc = substr($dataNascimento, 4, 4);        

        if (!checkdate($mesNasc, $diaNasc, $anoNasc))
        {
            $erroCadadtro  = ["DataNascimento" => "Data de nascimento inválida."
                ];
        }

        $cadastroUsuario = ["LoginID" => $dadosLogin['ID'],
                            "Nome" => $phpPost['cadNome'],
                            "DDDTelefone" => $DDDTelefone,
                            "Telefone" => $numTelefone,
                            "DataNascimento" => $anoNasc . "-". $mesNasc . "-" . $diaNasc
            ];

        $atualizaoCadastro = sendRest($endPoint['alterarcadastro'], $cadastroUsuario, "PUT");
        
        if ($atualizaoCadastro)
        {
            $dadosLogin = login($endPoint['login'], $_SESSION['bearer']);
        }
    }     

    if (!empty($phpPost['editarEndereco']) && $phpPost['editarEndereco'] == md5("endereco")) //editando endereço principal
    {    
        $deleteEnde = sendRest(str_replace("{IDEndereco}", $phpPost['endPrinCodEndereco'], $endPoint['delendereco']), ['delete' => ''], "DELETE");
        
        $dadosEndereco = ["ParceiroID" => $phpPost['endPrinParceiroID'],
                        "Destinatario" => $phpPost['endPrinDestinatario'],
                        "Identificacao" => "Endereço Principal",
                        "CEP" => preg_replace('/\D/', '', $phpPost['endPrinCEP']),
                        "Logradouro" => "Av Rudolf Dafferner",
                        "Numero" => $phpPost['endPrinNumero'],
                        "Complemento" => $phpPost['endPrinComplemento'],
                        "Bairro" => $phpPost['endPrinBairro'],
                        "CidadeID" => $phpPost['endPrinCodCidade'],
                        "Principal" => true,
            ];

        
        
    }
    
    ?>
    
    <script type="text/javascript">
        function alterarCadastro()
        {
            var retorno = true;
        
            var cadNome = $('#infoNome').val();
            var CadTelefone = $('#infoTelefone').val();
            var cadNascimento = $('#infoDtNascimento').val();

            if (cadNome.trim() == '')
            {
                var retorno = false;
                var erro = 'Preencha o campo \'Nome\'';
            }
        
            $('#retornoCadastro').html(erro);
        
            return retorno;
        }
    
        function trocarSenha()
        {
            document.altSenha.altSenhaAtual.value = '';
            document.altSenha.altNovaSenha.value = '';
            document.altSenha.altConfNovaSenha.value = '';
        
            $('#retornoSenha').html('...');
            
            return false;
        }
        
        function obterEndereco()
        {
            $('#resultCEP').html('');
            
            document.endPrinForm.endPrinLogradouro.value = 'Buscando endereço...';
            
            var endPrinCEP = $('#endPrinCEP').val();
            
            $.post('/_pages/buscarendereco.php', {postcep:endPrinCEP},
            function(data)
            {
                if (data.substring(0,2) == "!!")
                {
                    $('#resultCEP').html(data.substring(2));
                    document.endPrinForm.endPrinLogradouro.value = '';
                    document.endPrinForm.endPrinCidade.value = '';
                    document.endPrinForm.endPrinUF.value = '';
                }
                else
                {
                    var dadosEndereco = data.split("#");
                    
                    document.endPrinForm.endPrinCodCidade.value = dadosEndereco[0];
                    document.endPrinForm.endPrinLogradouro.value = dadosEndereco[1];
                    document.endPrinForm.endPrinCidade.value = dadosEndereco[2];
                    document.endPrinForm.endPrinUF.value = dadosEndereco[3];
                }
            });
            return false;
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
                        <li><a href="#troc-dev" data-toggle="tab">Trocas e devoluções</a></li>
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
                        <form name="cadFormAlterar" method="post" action="/" onsubmit="return alterarCadastro()">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nome</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadNome" id="infoNome" class="form-control" value="<?= $dadosLogin['Parceiro']['RazaoSocial'] ?>" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Data de nascimento</label>
                                <div class="col-sm-9 col-md-6">
                                        <input type="text" name="cadDtNascimento" id="infoDtNascimento" class="form-control" placeholder="DD/MM/AAAA" maxlength="10" value="<?= date_format(date_create($dadosLogin['Parceiro']['DataNascimento']), "d/m/Y") ?>" required="required" />
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="control-label col-sm-3">Telefone</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="cadTelefone" id="infoTelefone" class="form-control" placeholder="(00) 0000-0000" value="<?= (!empty($dadosLogin['Parceiro']['DDDTelefone'])) ? "(" . substr($dadosLogin['Parceiro']['DDDTelefone'], -2) . ") " : "" ?><?= $dadosLogin['Parceiro']['Telefone'] ?>" required="required" />
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
                            <input type="hidden" name="cadSalvar" value="<?= md5("editarcadastro") ?>">
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
                                            <?= $dadosLogin['Parceiro']['RazaoSocial'] ?><br/>
                                            <?= htmlentities($enderecoPrincipal['Logradouro']) ?>, <?= htmlentities($enderecoPrincipal['Numero']) ?> <?= (!empty($enderecoPrincipal['Complemento'])) ? " - " . htmlentities($enderecoPrincipal['Complemento']) : "" ?> <br/>
                                            <?= mascara($enderecoPrincipal['CEP'], "#####-###") ?> - <?= htmlentities($enderecoPrincipal['Bairro']) ?><br/>
                                            <?= htmlentities($enderecoPrincipal['Cidade']['Nome']) ?> - <?= htmlentities($enderecoPrincipal['Cidade']['Estado']['Sigla']) ?>
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
                                                echo "Seu endereço principal e de entrega são os mesmos no momento. Clique em editar para trocar.";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group conta-painel-enderecos">
                                    <div class="col-md-6">
                                        <div class="col-md-2"><a href="#meus-end-edit-principal" data-toggle="tab"><span class="glyphicon glyphicon-edit"></span> Editar</a></div>
                                        <div class="col-md-2"><a href=""><span class="glyphicon glyphicon-remove"></span> Excluir</a></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-2"><a href="#meus-end-edit-entrega" data-toggle="tab"><span class="glyphicon glyphicon-edit"></span> Editar</a></div>
                                        <div class="col-md-2"><a href=""><span class="glyphicon glyphicon-remove"></span> Excluir</a></div>
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
                        <form name="endPrinForm" id="endPrinForm" method="post" action="/">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Tipo do endereço</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinDescricaoTipo" id="endPrinDescricaoTipo" value="<?= $tipoEndereco[$enderecoPrincipal['Tipo']] ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Destinatário</label>
                                <div class="col-sm-9 col-md-6">
                                    <input name="endPrinDestinatario" id="endPrinDestinatario" type="text" class="form-control" value="<?= $enderecoPrincipal['Destinatario'] ?>" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">CEP</label>
                                <div class="col-sm-9 col-md-6">
                                    <div class="input-group">
                                        <input name="endPrinCEP" id="endPrinCEP" type="text" value="<?=  mascara($enderecoPrincipal['CEP'], "#####-###") ?>" class="form-control" required="required"/>
                                        <span class="input-group-addon"><a href="javascript:obterEndereco()">Buscar endereço</a></span>
                                    </div>
                                    <span id="resultCEP"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Logradouro</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinLogradouro" id="endPrinLogradouro" value="<?= $enderecoPorCEP['Tipo'] . " " . $enderecoPorCEP['Nome'] ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Número</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinNumero" id="endPrinNumero" value="<?= $enderecoPrincipal['Numero'] ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Complemento</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinComplemento" id="endPrinComplemento" value="<?= $enderecoPrincipal['Complemento'] ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Bairro</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinBairro" id="endPrinBairro" value="<?= $enderecoPrincipal['Bairro'] ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Cidade</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinCidade" id="endPrinCidade" value="<?= $enderecoPorCEP['Cidade']['Nome'] ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Estado</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="text" name="endPrinUF" id="endPrinUF" value="<?= $enderecoPorCEP['Cidade']['Estado']['Sigla'] ?>" disabled="disabled" class="form-control" required="required" />
                                </div>
                            </div>
                            
                            <button href="#meus-end" data-toggle="tab" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            
                            <input type="hidden" name="endPrinLogHidden" value="<?= $enderecoPorCEP['Tipo'] . " " . $enderecoPorCEP['Nome'] ?>">
                            <input type="hidden" name="endPrinCodCidade" value="<?= $enderecoPorCEP['Cidade']['ID'] ?>">
                            <input type="hidden" name="endPrinCodEndereco" value="<?= $enderecoPrincipal['ID'] ?>">
                            <input type="hidden" name="endPrinParceiroID" value="<?= $enderecos['ParceiroID'] ?>">
                            <input type="hidden" name="editarEndereco" value="<?= md5("endereco") ?>">
                        </form>
                    </div>
                </div>
                <!-- fim pane -->

                <!-- pane meus enderecos edit entrega-->
                <div class="tab-pane" id="meus-end-edit-entrega">
                    <div class="conta-painel">
                        <form>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Destinatário</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" value="entrega" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">CEP</label>
                                <div class="col-sm-9 col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" /><span class="input-group-addon"><a href="">Não sabe o CEP?</a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Endereço	</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Número	</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Complemento	</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Bairro	</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Cidade	</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Estado	</label>
                                <div class="col-sm-9 col-md-6">
                                    <select class="form-control">
                                        <option>Selecione o Estado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Telefone	</label>
                                <div class="col-sm-9 col-md-6"><input type="text" class="form-control" /></div>
                            </div>
                            <button href="#meus-end" data-toggle="tab" class="btn btn-default">Cancelar</button>
                            <button href="#meus-end" data-toggle="tab" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->
                
                <!-- pane troca devolucao envio -->
                <div class="tab-pane" id="troc-dev-send">
                    <div class="conta-painel">

                        <div class="row">
                            <div class="col-md-2">
                                Pedido nº 45298775
                            </div>
                            <div class="col-md-2">
                                Data do pedido:
                                10/01/2017
                            </div>
                            <div class="col-md-2">
                                Qtde. de itens:
                                1

                            </div>
                            <div class="col-md-2">
                                Total do pedido:
                                R$ 370,00

                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#troc-dev-msg" class="conta-painel-toggle" data-toggle="collapse">Ocultar </a>
                            </div>
                        </div>
                        <div class="well">

                            <div id="troc-dev-msg" class="collapse in text-center">
                                <p>E-mail enviado com sucesso!</p>
                                <p>Aguarde um de nossos atendentes entrar em contato por e-mail ou telefone.</p>
                                <p>Até breve.</p>
                                <button href="#troc-dev" data-toggle="tab" class="btn btn-default">Voltar</button>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- fim pane -->


                <!-- pane troca devolucao -->
                <div class="tab-pane" id="troc-dev">
                    <div class="conta-painel">

                        <div class="row">
                            <div class="col-md-2">
                                Pedido nº 45298775
                            </div>
                            <div class="col-md-2">
                                Data do pedido:
                                10/01/2017
                            </div>
                            <div class="col-md-2">
                                Qtde. de itens:
                                1

                            </div>
                            <div class="col-md-2">
                                Total do pedido:
                                R$ 370,00

                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#conta-entrega-01" class="conta-painel-toggle" data-toggle="collapse">Ocultar </a>
                            </div>
                        </div>

                        <div class="well">
                            <div id="conta-entrega-01" class="collapse">
                                <p>Envie um e-mail com sua dúvida respondendo o formulário abaixo:</p>
                                <form method="post" action="/minhaconta">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Nome" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="CPF/CNPJ" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" placeholder="Pedido" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" placeholder="E-mail" value="r.guazelli bol.com.br" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Telefone" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div><input type="text" class="form-control" placeholder="Assunto" /></div>
                                    </div>
                                    <div class="form-group">
                                        <div><textarea type="textarea" class="form-control" placeholder="Mensagem"></textarea></div>
                                    </div>
                                    <button href="#troc-dev" data-toggle="tab" class="btn btn-default">Cancelar</button>
                                    <button href="#troc-dev-send" data-toggle="tab" class="btn btn-primary">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- fim pane -->


                <!-- pane meus pedidos -->
                <div class="tab-pane" id="meus-ped">

                    <div class="conta-painel">

                        <div class="row">
                            <div class="col-md-2">
                                Pedido nº 45298775
                            </div>
                            <div class="col-md-2">
                                Data do pedido:
                                10/01/2017
                            </div>
                            <div class="col-md-2">
                                Qtde. de itens:
                                1

                            </div>
                            <div class="col-md-2">
                                Total do pedido:
                                R$ 370,00

                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#meus-ped-hide" class="conta-painel-toggle" data-toggle="collapse">Ocultar</a>
                            </div>
                        </div>
                        <div class="well">
                            <div id="meus-ped-hide" class="collapse in">
                                <div class="conta-painel-entrega">Entrega 1 de 2 - Entregue por <span>Hooray</span></div>
                                <div class="conta-checkpoint">
                                    <div class="row">
                                        <div class="linha">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-2 first visited">
                                                <div class="circulo"></div>
                                                <span>Pedido</span>
                                                Recebido
                                            </div>
                                            <div class="col-xs-2 active">
                                                <div class="circulo"></div>
                                                <span>Pagamento</span>
                                                Cancelado
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="circulo"></div>
                                                <span>Separação</span>
                                                Andamento
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="circulo"></div>
                                                <span>Transporte</span>
                                            </div>
                                            <div class="col-xs-2 last">
                                                <div class="circulo"></div>
                                                <span>Entrega</span>
                                            </div>
                                            <div class="col-xs-1"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <img src="./images/account-pedidos-thumb.png" alt="Camiseta Rip Curl"/>
                                    </div>
                                    <div class="col-md-7">
                                        Camiseta Rip Curl - cor Branco - Tamanho: GG<br />
                                        SKU - PU839384740172938
                                    </div>
                                    <div class="col-md-2">
                                        Quantidade: 1
                                    </div>
                                    <div class="col-md-2 text-right">
                                        R$ 150,00
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-md-offset-8">
                                        Total
                                    </div>
                                    <div class="col-md-2 text-right">
                                        R$ 150,00
                                    </div>
                                </div>

                                <div class="conta-painel-entrega">Entrega 2 de 2 - Entregue por <span>Water Sports</span></div>
                                <div class="conta-checkpoint">
                                    <div class="row">
                                        <div class="linha">
                                            <div class="col-xs-1"></div>
                                            <div class="col-xs-2 first visited">
                                                <div class="circulo"></div>
                                                <span>Pedido</span>
                                                Recebido
                                            </div>
                                            <div class="col-xs-2 active">
                                                <div class="circulo"></div>
                                                <span>Pagamento</span>
                                                Cancelado
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="circulo"></div>
                                                <span>Separação</span>
                                                Andamento
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="circulo"></div>
                                                <span>Transporte</span>
                                            </div>
                                            <div class="col-xs-2 last">
                                                <div class="circulo"></div>
                                                <span>Entrega</span>
                                            </div>
                                            <div class="col-xs-1"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <img src="./images/account-pedidos-thumb.png" alt="Camiseta Rip Curl"/>
                                    </div>
                                    <div class="col-md-7">
                                        Camiseta Rip Curl - cor Branco - Tamanho: GG<br />
                                        SKU - PU839384740172938
                                    </div>
                                    <div class="col-md-2">
                                        Quantidade: 1
                                    </div>
                                    <div class="col-md-2 text-right">
                                        R$ 150,00
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-md-offset-8">
                                        Total
                                    </div>
                                    <div class="col-md-2 text-right">
                                        R$ 220,00
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Endereço de Entrega:</label>
                                        <p>	Renato guazelli<br />
                                            Avenida Rouxinol 771<br />
                                            22 bloco B<br />
                                            Moema | 04093-002<br />
                                            Sao Paulo - SP<br />
                                            (11) 3018-3816</p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Forma de Pagamento</label>
                                        <p>
                                            <a href="#modal-boleto" data-toggle="modal"><img src="./images/icon-boleto.png" alt="Boleto" class="img-responsive"/></a>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total do pedido:</label>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        R$ 370,00
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- fim pane -->




                <!-- pane edit conta info senha -->
                <div class="tab-pane" id="inf-conta-edit-pass">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form name="altSenha" method="post" onsubmit="return trocarSenha()">
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
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>

                </div>
                <!-- fim pane -->


                <!-- pane edit conta info senha mensagem-->
                <div class="tab-pane" id="inf-conta-edit-info-pass-msg">
                    <div class="conta-painel conta-painel-edit-infos">
                        Senha redefinida</br></br></br>
                        <button href="#inf-conta" data-toggle="tab" class="btn btn-default">Voltar</button>
                    </div>

                </div>
                <!-- fim pane -->


                <!-- pane favoritos -->
                <div class="tab-pane" id="meus-fav">
                    <div class="conta-painel">
                        <div class="row">
                            <div class="col-md-1">
                                <img src="./images/account-pedidos-thumb.png" alt="Camiseta Rip Curl"/>
                            </div>
                            <div class="col-md-9">
                                Camiseta Rip Curl - cor Branco - Tamanho: GG<br />
                                SKU - PU839384740172938
                            </div>
                            <div class="col-md-2 text-right">
                                R$ 150,00
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <img src="./images/account-pedidos-thumb.png" alt="Camiseta Rip Curl"/>
                            </div>
                            <div class="col-md-9">
                                Camiseta Rip Curl - cor Branco - Tamanho: GG<br />
                                SKU - PU839384740172938
                            </div>
                            <div class="col-md-2 text-right">
                                R$ 150,00
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <img src="./images/account-pedidos-thumb.png" alt="Camiseta Rip Curl"/>
                            </div>
                            <div class="col-md-9">
                                Camiseta Rip Curl - cor Branco - Tamanho: GG<br />
                                SKU - PU839384740172938
                            </div>
                            <div class="col-md-2 text-right">
                                R$ 150,00
                            </div>
                        </div>

                    </div>
                </div>

                <!-- fim pane -->

            </div>
        </div>
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

            $.post('/_pages/cadastro.php', {postnome:cadNomeMC,
                                         postemail:cadEmailMC,
                                         posttelefone:cadTelefoneMC,
                                         postnascimento:cadDtNascimentoMC,
                                         postsexo:cadSexoMC,
                                         postcnpj:cadCNPJMC,
                                         postsenha:cadSenhaMC,
                                         postconfsenha:cadConfSenhaMC,
                                         postenviar:'<?= md5("cadastro") ?>'},
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
                }
            });
            return false;
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
                        <form name="loginFormMC" id="loginForm" method="post" action="/" onSubmit="return obterBearerMC()">
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
                        <form name="cadFormCompleto" method="post" action="/" onSubmit="return realizarCadastro()">
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
                                    <input type="text" name="cadTelefoneMC" id="cadTelefoneMC" class="form-control" placeholder="(00) 0000-0000" required="required" />
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
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->                    

                <!-- pane senha -->
                <div class="tab-pane" id="inf-senha">
                    <div class="conta-painel conta-painel-edit-infos">
                        <form method="post" action="/" onSubmit="return obterBearerMC()">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">E-mail</label>
                                <div class="col-sm-9 col-md-6">
                                    <input type="email" name="loginEmailMC" id="loginEmailMC" class="form-control" placeholder="E-mail" required="required" />
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12" id="resultBearerMC"></div>

                            <button type="reset" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </form>
                    </div>
                </div>
                <!-- fim pane -->                    
                
                
            </div>
        </div>
    </section>	
    
<?php    
}
?>