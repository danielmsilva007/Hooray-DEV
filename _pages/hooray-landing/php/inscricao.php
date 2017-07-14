<?php
if ($_POST[mailsent] == md5("enviar"))
{
	include_once("PHPMailer/class.phpmailer.php");

	$To      = "marketplace@hooray.com.br";
	$Subject = "Hooray - Pre inscricao";
	$body1   = "Nome: " . $_POST["nome"] . "\n\r";
	$body1  .= "E-mail: " . $_POST["email"]  . "\n\r";
	$body1  .= "Telefone: " . $_POST["telefone"]  . "\n\r";
	$body1  .= "Empresa: " . $_POST["empresa"]  . "\n\r";
	$body1  .= "CFP/CNPJ: " . $_POST["cpfoucnpj"] . "\n\r";
	$body1  .= "WebSite: " . $_POST["site"] . "\n\r";
	$body1  .= "Marcas que comercializa: " . $_POST["marcas"] . "\n\r";
	
	$categorias .= ($_POST["surf"] == "Surf") ? ", " . $_POST["surf"] : "";
	$categorias .= ($_POST["sup"] == "Stand up Paddle") ? ", " . $_POST["sup"] : "";
	$categorias .= ($_POST["kite"] == "Kite") ? ", " . $_POST["kite"] : "";
	$categorias .= ($_POST["wake"] == "Wake") ? ", " . $_POST["wake"] : "";
	$categorias .= ($_POST["caiaque"] == "Caiaque") ? ", " . $_POST["caiaque"] : "";
	$categorias .= ($_POST["canoa"] == "Canoa") ? ", " . $_POST["canoa"] : "";
	$categorias .= ($_POST["outros"] == "Outros") ? ", " . $_POST["outros"] : "";
	$categorias .= (!empty($_POST["outros"])) ? ", Outros: " . $_POST["outros"] : "";
	
	$body1  .= "Quais categorias de produto voce comercializa: ";
	$body1  .= substr($categorias, 1)  . "\n\r";

	$disponibilide .= ($_POST["pronta"] == "Pronta entrega") ? ", " . $_POST["pronta"] : "";
	$disponibilide .= ($_POST["encomenda"] == "Sob encomenda") ? ", " . $_POST["encomenda"] : "";
	$disponibilide .= ($_POST["custom"] == "Customizado") ? ", " . $_POST["custom"] : "";
	
	$body1  .= "Disponibilidade do produto: ";
	$body1  .= substr($disponibilide, 1)  . "\n\r";	
	
	$disponibilidade .= ($_POST["fabricante"] == "Fabricante") ? ", " . $_POST["fabricante"] : "";
	$disponibilidade .= ($_POST["distribuidor"] == "Distribuidor") ? ", " . $_POST["distribuidor"] : "";
	$disponibilidade .= ($_POST["importador"] == "Importador") ? ", " . $_POST["importador"] : "";
	
	$body1  .= "Atividade principal: ";
	$body1  .= substr($disponibilidade, 1) . "\n\r";	

	$nomeRemetente = "Hooray";
	$Host          = 'smtp.gmail.com';
	$Username      = 'marketplace@hooray.com.br';
	$Password      = 'hooray2019';
	$Port          = "587";

	$mail = new PHPMailer();
	$mail->IsSMTP();           // telling the class to use SMTP
	$mail->Host      = $Host;  // SMTP server
	$mail->SMTPDebug = 0;      // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;      // enable SMTP authentication
	$mail->SMTPSecure = "tls";     // TLS or SSL
	$mail->Port       = $Port;     // set the SMTP port for the service server
	$mail->Username   = $Username; // account username
	$mail->Password   = $Password; // account password
	
	$mail->SetFrom($Username, $nomeRemetente);
	$mail->Subject = $Subject;
	//$mail->MsgHTML($body1);

	$mail->Body = $body1;
	$mail->AddAddress($To);
	$mail->Send();
	
	$mensagemRetorno = 'Obrigado! Sua incrição foi enviada.';

	//if(!$mail->Send()) {
	//	$mensagemRetorno = 'Desculpe-nos, houve um erro no envio da sua mensagem: '. print($mail->ErrorInfo);
	//} else {
	//	$mensagemRetorno = 'Obrigado! Seu endereço de email foi cadastrado.';
	//}
}

?>	


<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Hooray - Inscrição</title>
	<meta name="description" content="">
	<meta name="keywords" content="">

    <meta name="author" content="">
    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="x-ua-compatible" content="IE=9">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <!--headerIncludes-->
    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="stylesheets/base.css">


    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Favicons
    ==================================================-->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114.png">

    <!-- Hotjar Tracking Code for www.hooray.com.br -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:405595,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>

</head>
<body>

<div id="page" class="page">
		<div class="light_gray_bg small_padding pix_builder_bg" style="outline-offset: -3px;">
            <div class="container">
                <div class="sixteen columns alpha center_text">
                    <img class="padding_left_10 logo-hooray-insc" src="images/uploads/Logo-Hooray-170px-novo.png" alt="Logo Hooray" >
                </div>
            </div>
        </div>
        <div class="hl3 pix_builder_bg" id="section_highlight_3">
        <div class="big_padding highlight-section">
            <div class="highlight-right pix_builder_bg"></div>
            <div class="container">
                <div class="sixteen columns">
                    <div class="eight columns alpha">
                        <br>
                    </div>
                    <div class="eight columns omega ">
                        <div class="highlight_inner">
                            <div class="left_text">
                                <h4 class="big_title margin_bottom"><strong>Pré-inscrição</strong></h4>
                                <p class="normal_text pixfort_app_3 calc_text">Preencha algumas informações para realizarmos seu cadastro na Hooray:</p>
								<div>
									<span class="hooray-desc" id="retornoEmail" style="color: red;"><?= $mensagemRetorno ?></span>
								</div>                                
								<form id="contact_form" class="pix_form" pix-confirm="hidden_pix_4" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <div id="result"></div>
                                    <input type="text" name="nome" id="name" placeholder="Nome" required="true">
                                    <input type="text" name="email" id="email" placeholder="Email" required="true">
                                    <label for="telefone"></label>
                                    <input type="tel" name="telefone" id="telefone" placeholder="Telefone" title="Preencha um telefone com DDD" required="true">
                                    <input type="text" name="empresa" id="empresa" placeholder="Nome da Empresa" required="true">
                                    <input type="text" name="cpfoucnpj" id="cpfoucnpj" placeholder="CNPJ ou CPF" required="true">
                                    <input type="text" name="site" id="site" placeholder="Sua empresa possui um site? Qual o endereço?" required="true">
                                    <input type="text" name="marcas" id="marcas" placeholder="Quais marcas você comercializa?" required="true">
                                    <p class="txt-insc">Quais categorias de produto você comercializa?</p>
                                    <label class="control control--checkbox">Surf
                                      <input type="checkbox" name="surf" id="surf" value="Surf"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Stand up Paddle
                                      <input type="checkbox" name="sup" id="sup" value="Stand up Paddle"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Kite
                                      <input type="checkbox" name="kite" id="kite" value="Kite"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Wake
                                      <input type="checkbox" name="wake" id="wake" value="Wake"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Caiaque
                                      <input type="checkbox" name="caiaque" id="caiaque" value="Caiaque"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Canoa
                                      <input type="checkbox" name="canoa" id="canoa" value="Canoa"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Outros?
                                      <input type="checkbox" name="outros" id="outros" value="Outros"/>
                                      <div class="control__indicator"></div>
                                    </label>

                                    <br><input type="text" name="outros-cat-define" id="outros-cat-define" placeholder="Quais outros produtos você vende?" required="true" />

                                    <p class="txt-insc">Disponibilidade do produto:</p>
                                    <label class="control control--checkbox">Pronta entrega
                                      <input type="checkbox" name="pronta" id="pronta" value="Pronta entrega"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Sob encomenda
                                      <input type="checkbox" name="encomenda" id="encomenda" value="Sob encomenda"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Customizado
                                      <input type="checkbox" name="custom" id="custom" value="Customizado"/>
                                      <div class="control__indicator"></div>
                                    </label>


                                    <p class="txt-insc">Atividade principal:</p>

                                    <label class="control control--checkbox">Fabricante
                                      <input type="checkbox" name="fabricante" id="fabricante" value="Fabricante"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Distribuidor
                                      <input type="checkbox" name="distribuidor" id="distribuidor" value="Distribuidor"/>
                                      <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--checkbox">Importador
                                      <input type="checkbox" name="importador" id="importador" value="Importador"/>
                                      <div class="control__indicator"></div>
                                    </label>

									<button type="submit" class="submit_btn green_1_bg top30" id="submit_btn_6" >
                                      Enviar informações
                                    </button>
                                  <input type="hidden" name="mailsent" value="<?= md5("enviar") ?>">
								  </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
    <div id="hidden_pix_4" class="confirm_page confirm_page_4 green_1_bg pix_builder_bg ">
        <div class="pixfort_real_estate_4">
            <div class="confirm_header">Pré-inscrição confirmada!</div>
            <div class="confirm_text">
                Agradecemos suas informações. Em breve entraremos em contato para finalizar seu cadastro.
            </div>
            <div class="center_text padding_bottom_60">
                <ul class="bottom-icons confirm_icons center_text big_title pix_inline_block">
                    <li><a class="pi pixicon-facebook6 white" target="_blank" href="https://www.facebook.com/hooraybr/"></a></li>
                    <li><a class="pi pixicon-instagram1 white" target="_blank" href="https://www.instagram.com/hooraybrasil/"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="copyright text-center">
          © 2017 HOORAY | Todos os direitos reservados
        </div>
  </div>

<!-- JavaScript
================================================== -->
<script src="js-files/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="js-files/jquery.easing.1.3.js" type="text/javascript"></script>
<script type="text/javascript" src="js-files/jquery.common.min.js"></script>
<script src="js-files/ticker.js" type="text/javascript"></script>
<script src="assets/js/appear.min.js" type="text/javascript"></script>
<script src="js-files/jquery.ui.touch-punch.min.js"></script>
<script src="js-files/bootstrap.min.js"></script>
<script src="js-files/bootstrap-switch.js"></script>
<script src="js-files/masking/input-mask.min.js"></script>

<script>
    // Mask
    var selector = document.getElementById('telefone');
    $(document).ready(function(){
      $(selector).inputmask("(99) 99999-9999");  //static mask
    });

    // Retrieve Saved
    var user = JSON.parse(localStorage.getItem('hooray-data')).user || {};
    if(user) {
        $('#name').val(user.name);
        $('#email').val(user.email);
        $('#telefone').val(user.phone);
    }
</script>

<script src="assets/js/appear.min.js" type="text/javascript"></script>
<script src="assets/js/animations.js" type="text/javascript"></script>
</body>
</html>
