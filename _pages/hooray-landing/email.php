<?php
print "<pre>";
include_once("PHPMailer/class.phpmailer.php");

$To      = "daniel.silva@apiconsulting.com.br"; // "marketplace@hooray.com.br";
$Subject = "Hooray - Aumente suas vendas";
$body1 = "teste gmail";

$nomeRemetente = "Hooray";
$Host          = 'smtp.gmail.com';
$Username      = 'marketplace@hooray.com.br';
$Password      = 'hooray2019';
$Port          = "465"; // "587";

$mail = new PHPMailer();
$mail->IsSMTP();           // telling the class to use SMTP
$mail->Host      = $Host;  // SMTP server
$mail->SMTPDebug = 1;      // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
$mail->SMTPAuth   = true;      // enable SMTP authentication
$mail->SMTPSecure = "ssl"; // "tls";     // TLS or SSL
$mail->Port       = $Port;     // set the SMTP port for the service server
$mail->Username   = $Username; // account username
$mail->Password   = $Password; // account password

$mail->SetFrom($Username, $nomeRemetente);
$mail->Subject = $Subject;
//$mail->MsgHTML($body1);

$mail->Body = $body1;
$mail->AddAddress($To);
//$mail->Send();

$mensagemRetorno = 'Obrigado! Seus dados foram enviados.';

if(!$mail->Send()) 
{
	print 'erro: ' . print($mail->ErrorInfo);
} 
else 
{
	print 'Enviado';
}
print "</pre>";
?>