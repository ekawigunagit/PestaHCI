<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP; 

include "../../config/config.php";
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/PHPMailer.php';

$data = $_POST;


if ((cekstring($_POST['email'])) || (cekstring($_POST['phone'])) || (cekstring($_POST['city'])) || (cekstring($_POST['area'])) || (cekstring($_POST['name'])) || (cekstring($_POST['product_id'])) || (cekstring($_POST['brand_product'])) || (cekstring($_POST['category_name'])) || (cekstring($_POST['product_name'])) || (cekstring($_POST['hadiah']))) {
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('Akses Error');
    window.location.href='../../index.php';
    </script>");
} else {
    $nama_penerima = $_POST['name'];
    $email_penerima = $_POST['email'];
    $productName = $_POST['product_name'];
    //echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
    $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['utm_source'] = "Pesta Online";
    $data['utm_campaign'] = $_POST['brand_product']; // Brand_id
    $data['utm_medium'] = $_POST['category_name']; // Category Product
    $data['utm_content'] = $_POST['product_name']; // Product Name
    $data['gclid'] = $_POST['hadiah'];

    include "../../admin/sendnotif.php";

    // $testing = isiBody($nama_penerima, $productName);
    // echo $testing; exit;


    // echo"name : " . $data['name']; exit;

    function sendEmail(){
        $nama_penerima = $_POST['name'];
        $email_penerima = $_POST['email'];
        $productName = $_POST['product_name'];
    
        $email_pengirim = 'halloflandy@gmail.com';
        $password = 'mamunah12';
        $mail = new PHPMailer;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';//Set the SMTP server to send through
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = $email_pengirim;//SMTP username
        $mail->Password   = $password;//SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;//Enable implicit TLS encryption
        $mail->Port       = 465;   
        
        //Recipients
        $mail->setFrom($email_pengirim, 'Info Pesta Home Credit');
        $mail->addAddress($email_penerima, $nama_penerima);     //Add a recipient             //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
    
        //Message
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "Konfirmasi Pengajuan - PESTA Home Credit";
        $mail->Body    = isiBody($nama_penerima, $productName);
    
        $mail->AltBody = "Terima kasih, kamu baru saja berhasil melakukan transaksi". $nama_penerima .",";
    
        $email = $mail->send();
        return $email;
    }   

    //Send Email Notification
    sendEmail();
    //Data passing 

    print_r($data); exit;
    echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
    $data['value_dump'] = '{"commodity":"'.$_POST['product_name'].'"}';
    echo ($data['value_dump']); exit;

    // $method = "POST";
    // // $url = "https://apixweb-dev.homecredit.co.id/api/v1/form/pesta-online";
    // $url = "https://apixweb.homecredit.co.id/api/v1/form/pesta-online";
    // $response = _curlPost($url, $data, $method);
    // $result = json_encode($response);
    // echo json_decode($result);

    // // echo "ID : " . $_POST['product_id']; exit;
    // if (isset($_POST['product_id']) && $_POST['product_id'] != "") {
    //     $ambilproduct_en = sekuriti($_POST['product_id'], 'encrypt');
    //     header('location:../../index.php?page=thankyouPage&idpr=' . $ambilproduct_en);
    // } else if (isset($_POST['promo_id']) && $_POST['promo_id'] != "") {
    //     $ambilpromo_en = sekuriti($_POST['promo_id'], 'encrypt');
    //     header('location:../../index.php?page=thankyouPagePromo&idpr=' . $ambilpromo_en);
    // }
}