<?php
include "../../config/config.php";
$data = $_POST;
if ((cekstring($_POST['email'])) || (cekstring($_POST['phone'])) || (cekstring($_POST['city'])) || (cekstring($_POST['area'])) || (cekstring($_POST['name'])) || (cekstring($_POST['product_id'])) || (cekstring($_POST['brand_product'])) || (cekstring($_POST['category_name'])) || (cekstring($_POST['product_name'])) || (cekstring($_POST['hadiah']))) {
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('Akses Error');
    window.location.href='../../index.php';
    </script>");
} else {
    //echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
    $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['utm_source'] = "Pesta Online";
    $data['utm_campaign'] = $_POST['brand_product']; // Brand_id
    $data['utm_medium'] = $_POST['category_name']; // Category Product
    $data['utm_content'] = $_POST['product_name']; // Product Name
    $data['gclid'] = $_POST['hadiah'];
    // print_r($data); exit;
    //echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
    //$data['value_dump'] = '{"commodity":"'.$_POST['product_name'].'"}';
    // echo ($data['value_dump']); exit;

    $method = "POST";
    // $url = "https://apixweb-dev.homecredit.co.id/api/v1/form/pesta-online";
    $url = "https://apixweb.homecredit.co.id/api/v1/form/pesta-online";
    $response = _curlPost($url, $data, $method);
    $result = json_encode($response);
    echo json_decode($result);

    // echo "ID : " . $_POST['product_id']; exit;
    if (isset($_POST['product_id']) && $_POST['product_id'] != "") {
        $ambilproduct_en = sekuriti($_POST['product_id'], 'encrypt');
        header('location:../../index.php?page=thankyouPage&idpr=' . $ambilproduct_en);
    } else if (isset($_POST['promo_id']) && $_POST['promo_id'] != "") {
        $ambilpromo_en = sekuriti($_POST['promo_id'], 'encrypt');
        header('location:../../index.php?page=thankyouPagePromo&idpr=' . $ambilpromo_en);
    }
}
