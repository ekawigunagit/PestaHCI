<?php
include "../../config/config.php";
$data = $_POST;
//echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$data['utm_source'] = "Pesta Online";
$data['utm_campaign'] = $_POST['brand_product'];
$data['utm_medium'] = $_POST['product_name'];
$data['utm_content'] = $_POST['hadiah'];
//print_r($data); exit;
//echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
//$data['value_dump'] = '{"commodity":"'.$_POST['product_name'].'"}';
// echo ($data['value_dump']); exit;
$method = "POST";
$url = "https://apixweb-dev.homecredit.co.id/api/v1/form/pesta-online";
$response = _curlPost($url,$data, $method);
// var_dump($response); exit;
$result = json_encode($response);
echo json_decode($result);
header('location:../../index.php?page=thankyouPage&idpr='. $_POST['product_id']);
?>