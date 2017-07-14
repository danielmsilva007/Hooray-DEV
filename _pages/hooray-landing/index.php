<?php
if ($_POST[mailsent] == md5("enviar"))
{
	include_once("PHPMailer/class.phpmailer.php");

	$To      = "marketplace@hooray.com.br";
	$Subject = "Hooray - Aumente suas vendas";
	$body1   = "Nome: ".$_POST["nome"] . "\n\r";
	$body1  .= "E-mail: ".$_POST["email"]  . "\n\r";
	$body1  .= "telefone: ".$_POST["telefone"];

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
	
	$mensagemRetorno = 'Obrigado! Seus dados foram enviados.';

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
    <title>Hooray</title>
	<meta name="description" content="">
	<meta name="keywords" content="">

    <meta name="author" content="">
    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="x-ua-compatible" content="IE=9">
    <!-- Font Awesome -->
    <link href="stylesheets/font-awesome.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <!--headerIncludes-->
    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="stylesheets/menu.css">
    <link rel="stylesheet" href="stylesheets/flat-ui-slider.css">
    <link rel="stylesheet" href="stylesheets/base.css">
    <link rel="stylesheet" href="stylesheets/skeleton.css">
    <link rel="stylesheet" href="stylesheets/landings.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/landings_layouts.css">
    <link rel="stylesheet" href="stylesheets/pixicon.css">
    <link href="assets/css/animations.min.css" rel="stylesheet" type="text/css" media="all" />

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

  <div class="" id="section_header_6">
      <div class="header_style header_nav_1 pix_builder_bg" style="outline-offset: -3px;">
          <div class="container">



              <div class="one-third column">
                  <nav role="navigation" class="navbar navbar-white navbar-embossed navbar-lg pix_nav_1">
                      <div class="container">
                        <div class="one-third column">
                            <div class="small_padding">
                              <div class="navbar-header">
                                  <button data-target="#navbar-collapse-02" data-toggle="collapse" class="navbar-toggle" type="button">
                                      <span class="sr-only">Navegação</span>
                                  </button>
                              </div>
                                <img class="logo-hooray" src="images/uploads/Logo-Hooray-170px-novo.png" alt="Logo Hooray">
                            </div>
                        </div>
                      </div><!-- /.container -->
                  </nav>
              </div>

              <div class="two-thirds column">
                  <div class="padding_25 right_socials">
                      <ul class="bottom-icons">
                          <li><a class="pi pixicon-facebook2" target="_blank" href="https://www.facebook.com/hooraybr/"></a></li>

                          <li><a class="pi pixicon-instagram" target="_blank" href="https://www.instagram.com/hooraybrasil/"></a></li>
                      </ul>
                  </div>
              </div>

              <div id="navbar-collapse-02" class="collapse navbar-collapse">
                  <div class="one-third column">
                    <ul class="menu-principal nav nav-justified">
                      <li class="active propClone"><a href="#">Home</a></li>
                      <li class="propClone"><a href="#section_app_2">Benefícios</a></li>
                      <li class="propClone"><a href="#section_text_2">FAQ</a></li>
                  </ul>
                </div>
              </div><!-- /.navbar-collapse -->

          </div><!-- container -->
      </div>
  </div>

<!-- fim header -->

    <div class="slider-hooray-main dark" id="section_normal_4_1">
            <div class="container">
                <div class="sixteen columns">

                  <div class="six columns form-slider">

                    <div>
                      <p class="big_title">Aumente suas vendas</p>
                      <p class="hooray-desc" style="">Conheça a Hooray, o marketplace especializado em esportes outdoor. O lugar ideal para sua empresa anunciar produtos e alavancar as vendas de maneira segura e rápida. Faça sua pré-inscrição e conheça todas as vantagens deste canal de vendas único.</p>
                    </div>
					
					<div>
						<span class="hooray-desc" id="retornoEmail" style="color: red;"><?= $mensagemRetorno ?></span>
					</div>

                    <form id="contact_form" class="pix_form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
						<div id="result"></div>
						<input type="text" name="nome" id="name" placeholder="Nome" class="" required="">
						<input type="email" name="email" id="email" placeholder="Email" class="" required="">
						<input type="text" name="telefone" id="telefone" placeholder="Telefone para contato" class="" required="">
						<span class="send_btn">
							<button type="submit" class="submit_btn" id="submit_btn_6">
								<span id="botaoConheca">Conheça a Hooray</span>
							</button>
						</span>
						<input type="hidden" name="mailsent" value="<?= md5("enviar") ?>">
					</form>
                    <div class="clearfix"></div>
                  </div>
                </div>
	       </div>
        </div>
		<div class="pixfort_normal_1" id="section_normal_3">
        <div class="adv_st pix_builder_bg">
            <div class="container">
                <div class="sixteen columns">
                    <div class="one-third column onethird_style alpha">
                        <span class="circle slow_fade pix_builder_bg2 animate-in" src="images/uploads/ICONES-hooray_1.png" style="background-image: none; background-color: rgba(0, 0, 0, 0); padding-top: 15px; padding-bottom: 15px; box-shadow: none; border-color: rgb(241, 100, 112); border-width: 3px;" data-anim-type="bounce-in" data-anim-delay="1">
                            <img src="images/uploads/ICONES-hooray_1.png" alt="" style="border-radius: 0px; border-color: rgb(68, 68, 68); border-style: none; border-width: 1px; width: 50px; height: 50px;" class="animate-in" data-anim-type="bounce-in" data-anim-delay="5">
                        </span>
                        <div class="comment_style">
                            <span class="editContent"><span class="c1_style">
<span class="animate-in slow-mo" src="images/uploads/ICONES-hooray_1.png" style="color: rgb(34, 34, 34); font-size: 26px; background-color: rgba(0, 0, 0, 0);" data-anim-type="fade-in" data-anim-delay="1">Cadastre sua empresa</span></span></span>

                        </div>
                    </div>
                    <div class="one-third column onethird_style alpha">
                        <span class="circle slow_fade pix_builder_bg2 animate-in" src="images/uploads/ICONES-hooray_3.png" style="background-image: none; background-color: rgba(0, 0, 0, 0); padding-top: 15px; padding-bottom: 15px; box-shadow: none; border-color: rgb(241, 100, 112); border-width: 3px;" data-anim-type="bounce-in-up" data-anim-delay="4">
                            <img src="images/uploads/ICONES-hooray_2.png" alt="" style="border-radius: 0px; border-color: rgb(68, 68, 68); border-style: none; border-width: 1px; width: 50px; height: 50px;" class="animate-in" data-anim-type="bounce-in-big" data-anim-delay="4">
                        </span>

                        <div class="comment_style quebra">
                            <span class="editContent"><span class="c1_style">
<span class="animate-in slow-mo" src="images/uploads/ICONES-hooray_1.png" style="color: rgb(34, 34, 34); font-size: 26px; background-color: rgba(0, 0, 0, 0);" data-anim-type="fade-in" data-anim-delay="3">Suba seus produtos</span></span></span>

                        </div>
                    </div>



                    <div class="one-third column onethird_style alpha">
                        <span class="circle slow_fade pix_builder_bg2 animate-in" src="images/uploads/ICONES-hooray_3.png" style="background-image: none; background-color: rgba(0, 0, 0, 0); padding-top: 15px; padding-bottom: 15px; box-shadow: none; border-color: rgb(241, 100, 112); border-width: 3px;" data-anim-type="bounce-in-down" data-anim-delay="6">
                            <img src="images/uploads/ICONES-hooray_3.png" alt="" style="border-radius: 0px; border-color: rgb(68, 68, 68); border-style: none; border-width: 1px; width: 50px; height: 50px;" class="animate-in" data-anim-type="bounce-in-large" data-anim-delay="6">
                        </span>
                        <div class="comment_style quebra">
                            <span class="editContent"><span class="c1_style">
<span class="animate-in slow-mo" src="images/uploads/ICONES-hooray_1.png" style="color: rgb(34, 34, 34); font-size: 26px; background-color: rgba(0, 0, 0, 0);" data-anim-type="fade-in" data-anim-delay="5">Comece a vender</span></span></span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pixfort_app_3" id="section_app_2">
		<div class="cal_style pix_builder_bg" src="images/uploads/ICONES-hooray_content.png">
			<div class="container">
				<div class="sixteen columns pad_top">

                    <div class="one-third column alpha">
						<div class="img_st">
							<img src="images/uploads/ICONES-hooray_mkt.png" alt="marketing">
						</div>
						<div class="ctext_style">
							<p class="calc_st editContent">Marketing</p>
							<p class="calc_text editContent">Página exclusiva da sua marca e fluxo de visitas de um site especializado, gerando maior conversão de vendas.</p>
						</div>
             		</div>
             		<div class="one-third column alpha">
						<div class="img_st">
							<img src="images/uploads/ICONES-hooray_admin.png" alt="Tecnologia">
						</div>
						<div class="ctext_style">
							<p class="calc_st editContent">Tecnologia</p>
							<p class="calc_text editContent">
	Plataforma de e-commerce completa  com possibilidade de integração com sua estratégia omnichannel.
</p>
						</div>
             		</div>
             		<div class="one-third column omega">
						<div class="img_st">
							<img src="images/uploads/ICONES-hooray_custom.png" alt="Customização">
						</div>
						<div class="ctext_style">
							<p class="calc_st editContent">Customização</p>
							<p class="calc_text editContent">
Alavanque suas vendas sob encomenda com o visualizador de produtos 3D (disponível para pranchas).
</p>
						</div>
             		</div>
        		</div>
        		<div class="sixteen columns pad_top pad_down">
             		<div class="one-third column  alpha">
						<div class="img_st">
							<img src="images/uploads/ICONES-hooray_content.png" alt="Conteúdo">
						</div>
						<div class="ctext_style">
							<p class="calc_st editContent">Conteúdo</p>
							<p class="calc_text editContent"><span id="docs-internal-guid-82d48206-e1e0-0fdc-9ea3-2e49f4318a3e">
Tudo sobre esporte outdoor, com notícias, eventos e reviews.<br>
<br>
</span></p>
						</div>
	             	</div>
	             	<div class="one-third column alpha">
						<div class="img_st">
							<img src="images/uploads/ICONES-hooray_atendi.png" alt="Atendimento">
						</div>
						<div class="ctext_style">
							<p class="calc_st editContent">Atendimento</p>
							<p class="calc_text editContent">Auxilia o cliente durante todo o processo tirando dúvidas e facilitando sua compra.</p>
						</div>
					</div>
	             	<div class="one-third column  omega">
						<div class="img_st">
							<img src="images/uploads/ICONES-hooray_pay.png" alt="Pagamento">
						</div>
						<div class="ctext_style">
							<p class="calc_st editContent">Pagamento</p>
							<p class="calc_text editContent">
	Solução completa de meios de pagamento com segurança e rapidez.
</p>
<p></p>
						</div>
	             	</div>
	        	</div>
			</div>
		</div>
	</div><div class="slider-hooray" id="section_normal_4_1">
            <div class="container">
                <div class="sixteen columns center_text">
                    <p class="title_56 editContent">Faça seu cadastro e comece a vender</p>
                    <div>

                    </div>
                    <a href="./inscricao.php" class="active_bg_open pix_button btn_normal btn_big  bold_text orange_bg" name="fff" style="">
                        <span>Inscreva-se</span>
                    </a>
                </div>
           </div>
        </div><div class="light_gray_bg big_padding pix_builder_bg " id="section_text_2">
            <div class="container">
                <div class="fourteen columns offset-by-one">
                    <div class="event_box row pix_builder_bg">

                            <div class="padding_15 hor_padding">
                                <h4>
                                    <strong>FAQ - Perguntas Frequentes</strong>
                                </h4>
                                <br>
                                <p class="editContent normal_text"><strong>Como Funciona?</strong> <br></p>
                                    <ul><li>Após aprovação cadastral, seus produtos são anunciados no site.</li>
                                        <li>Fazemos o marketing para a geração de tráfego e divulgação de conteúdo.</li>
                                        <li>O cliente realiza suas compras 100% no site da Hooray.</li>
                                        <li>A central de atendimento Hooray auxilia o cliente durante o processo de compra.</li>
                                        <li>Confirmada a venda, a emissão da nota fiscal e o envio do produto são feitos por você.</li>
                                        <li>O pagamento é repassado à você, descontado um percentual de comissão pré-acordado.</li>
                                    </ul>
<br>
<p class="editContent normal_text"><strong>Qual a documentação necessária para cadastro?</strong><br></p>
<ul><li>Contrato Social ou relato da Junta Comercial</li>
    <li>Cartão CNPJ</li>
    <li>Comprovante bancário </li>
    <li>Dados de Contato</li>
    <li>Adesão aos Termos e Condições Gerais </li>
</ul><br>
<p class="editContent normal_text"><strong>Quais as vantagens para quem compra? </strong><br></p>
<ul>
	<li>As melhores marcas do esporte outdoor num só lugar</li>
	<li>Central de atendimento especializada: de praticante para praticante</li>
	<li>Segurança na compra: várias formas de pagamento com garantia e proteção.</li>
	<li>Segurança na entrega: pedido rastreado</li>
	<li>Conteúdo especializado: compre, experimente, compartilhe e participe de eventos do seu esporte</li>
  <li>Ferramenta de customização: escolha as características do produto e veja o resultado online em 3D (exclusivo para pranchas)</li>
</ul>
                            </div>
                    </div>
                </div>
            </div>
        </div><div class="pixfort_text_4 pix_builder_bg" id="section_text_4" style="outline-offset: -3px;">
        <div class="footer3">
        <div class="container ">
            <div class="nine columns alpha">
            	<div class="content_div area_1">
                    <img src="images/uploads/Logo-Hooray-170px-novo.png" class="pix_footer_logo" alt="Hooray" style="border-radius: 0px; border-color: rgb(68, 68, 68); border-style: none; border-width: 1px; width: 170px; height: 71px;">
                	<p class="normal_text editContent">Esporte Outdoor.</p>
                    <ul class="bottom-icons">
                        <li><a class="pi pixicon-facebook2" target="_blank" href="https://www.facebook.com/hooraybr/"></a></li>

                        <li><a class="pi pixicon-instagram" target="_blank" href="https://www.instagram.com/hooraybrasil/"></a></li>
                    </ul>
                </div>
            </div>
            <!--<div class="five columns">
				<div class="content_div area_2">
                	<ul class="footer3_menu">
                        <li><a href="#" class=""><span class="editContent">Home</span></a></li>
                        <li><a href="#" class=""><span class="editContent">Institucional</span></a></li>
                        <li><a href="#" class=""><span class="editContent">Termos de Uso</span></a></li>
                        <li><a href="#" class=""><span class="editContent">Política de Privacidade</span></a></li>
                        <li><a href="#" class=""><span class="editContent">Marketplace</span></a></li>
                        <li><a href="#" class=""><span class="editContent">Mapa do Site</span></a></li>

                    </ul>
                </div>
            </div>-->
            <div class="five columns">
				<div class="content_div area_3">
					<span class=""><span class="editContent big_number">11 2892-5181</span><br><br>
        <span class="editContent big_number">11 96474-5957</span></span>
          <span class=""><span class="editContent small_bold light_color">WhatsApp </span></span><br><br>
                    <h4 class="editContent med_title hooray-link"> <a href="mailto:marketplace@hooray.com.br?subject=Quero%20conhecer%20melhor%20a%20Hooray"> marketplace@hooray.com.br</a></h4>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="copyright text-center">
      		© 2017 HOORAY | Todos os direitos reservados
      	</div>
  </div>

  <!-- Return to Top -->
  <a href="javascript:" id="return-to-top"><i class="pi pixicon-arrow-up"></i></a>





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

	$('#submit_btn_6').click(function() {
		var user = {
			name: $('#name').val(),
			email: $('#email').val(),
			phone: $('#telefone').val()
		}
    });
</script>

<script> //scroll to top
$(window).scroll(function() {
  if ($(this).scrollTop() >= 50) { // If page is scrolled more than 50px
    $('#return-to-top').fadeIn(200); // Fade in the arrow
  } else {
    $('#return-to-top').fadeOut(200); // Else fade out the arrow
  }
});
$('#return-to-top').click(function() { // When arrow is clicked
  $('body,html').animate({
    scrollTop: 0 // Scroll to top of body
  }, 500);
});
</script>

<script src="assets/js/appear.min.js" type="text/javascript"></script>
<script src="assets/js/animations.js" type="text/javascript"></script>
</body>
</html>
