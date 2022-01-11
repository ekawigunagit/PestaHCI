<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include "../../config/config.php";
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/PHPMailer.php';

$data = $_POST;

// var_dump(cekstring($_POST['name'])); exit;
// var_dump($data); exit;
foreach ($data as $key => $value) {
    if (cekstring($data[$key])) {
        // echo "ERRORnya INI : " . $data[$key];
        // echo "Test";
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('Akses Error');
        window.location.href='../../index.php';
        </script>");
        exit;
    }
}

if (isset($_POST['tipe'])) {
    if ($_POST['tipe'] == 'dariPromo') {
        $ambildata = "SELECT * FROM promos WHERE id='$_POST[promo_id]'";
        $queryambildata = mysqli_query($koneksi, $ambildata);
        while ($showambildata = mysqli_fetch_array($queryambildata)) {
            $productName = $showambildata['title_promo'];
        }
    } else if ($_POST['tipe'] == 'dariProduk') {
        $ambildata = "SELECT * FROM products WHERE id='$_POST[product_id]'";
        $queryambildata = mysqli_query($koneksi, $ambildata);
        while ($showambildata = mysqli_fetch_array($queryambildata)) {
            $productName = $showambildata['product_name'];
        }
    }
}

// echo "Nama Promo / Product : " . $productName; exit;

$nama_penerima = $_POST['name'];
$email_penerima = $_POST['email'];
//echo $_POST['product_name'] . "<br />" . $_POST['brand_product']; exit;
$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$data['utm_source'] = "Pesta Online";
$data['utm_campaign'] = $_POST['brand_product']; // Brand_id
$data['utm_medium'] = $_POST['category_name']; // Category Product
$data['utm_content'] = $productName; // Product Name
$data['gclid'] = $_POST['hadiah'];

// var_dump($data); exit;
// $testing = isiBody($nama_penerima, $productName);
// echo $testing; exit;
// echo"name : " . $data['name']; exit;


// Include Body
include "sendnotif.php";

// Function Send Email
function sendEmail($productName)
{
    $nama_penerima = $_POST['name'];
    $email_penerima = $_POST['email'];

    $deskripsi = isset($_POST['product_id']) ? " Produk " : " Promo ";

    $email_pengirim = 'no-reply@pestahomecredit.com';
    $password = 'noreply@p3st4';
    $mail = new PHPMailer;
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'srv115.niagahoster.com'; //Set the SMTP server to send through smtp.gmail.com
    $mail->SMTPAuth   = true; //Enable SMTP authentication
    $mail->Username   = $email_pengirim; //SMTP username
    $mail->Password   = $password; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port       = 465;

    //Recipients
    $mail->setFrom($email_pengirim, 'Pesta Homecredit');
    $mail->addAddress($email_penerima, $nama_penerima);
    //Add a recipient             
    //Name is optional
    $mail->addReplyTo($email_pengirim, 'Pesta Homecredit');

    //Message
    $mail->isHTML(true);
    //Set email format to HTML
    $mail->Subject = 'Konfirmasi Pengajuan - PESTA Home Credit';
    $mail->Body    = isiBody($nama_penerima, $productName, $deskripsi);

    $mail->AltBody = 'Terima kasih, kamu baru saja berhasil melakukan transaksi' . $nama_penerima . ',';

    $email = $mail->send();
    return $email;
}


//Send Email Notification
sendEmail($productName);

// print_r($data);
// exit;

$method = "POST";
// $url = "https://apixweb-dev.homecredit.co.id/api/v1/form/pesta-online";
$url = "https://apixweb.homecredit.co.id/api/v1/form/pesta-online";
$response = _curlPost($url, $data, $method);
$result = json_encode($response);
echo json_decode($result);



if (isset($_POST['tipe'])) {
    if ($_POST['tipe'] == 'dariPromo') {
        $ambilpromo_en = sekuriti($_POST['promo_id'], 'encrypt');
        // echo "<br />Dari Promo : " . $ambilpromo_en;
        header('location:../../index.php?page=thankyouPagePromo&idpr=' . $ambilpromo_en);
    } else if ($_POST['tipe'] == 'dariProduk') {
        $ambilproduct_en = sekuriti($_POST['product_id'], 'encrypt');
        // echo "<br />Dari Product : " . $ambilproduct_en;
        header('location:../../index.php?page=thankyouPage&idpr=' . $ambilproduct_en);
    }
}