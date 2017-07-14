<?php 

$post = [
    'type' => 'costumer',
    'client_id' => 'dR39HhAA',
    'client_secret'   => 'ljQ341fO',
];

$ch = curl_init('http://app.rededigitalchevrolet.com.br/auth');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
$getToken = curl_exec($ch);

$json = json_decode($getToken);

echo "<pre>";
// Produto
$product = file_get_contents(
	'http://app.rededigitalchevrolet.com.br/product/' . $json->access_token
);
echo $product;

 //Preco
 $price = file_get_contents(
 	'http://app.rededigitalchevrolet.com.br/price/' . $json->access_token
 );
 echo $price;

 //Saldo
 $balance = file_get_contents(
 	'http://app.rededigitalchevrolet.com.br/price/' . $json->access_token
 );
 echo $balance;

 //Pedido
 $request = file_get_contents(
 	'http://app.rededigitalchevrolet.com.br/request/' . $json->access_token
 );
 echo $request;

 echo "</pre>";