<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include "../../config/config.php";
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/PHPMailer.php';

$data = $_POST;
$errors = [];
foreach ($data as $key => $value) {
    if (cekstring($data[$key])) {
        $errors[$key][] = 'The ' . $key . ' is invalid format.';
    } else if (trim($data[$key]) == '') {
        $errors[$key][] = 'The ' . $key . ' is required.';
    } else if (strtolower($key) == 'phone' && !preg_match("/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/", $data[$key])) {
        $errors[$key][] = 'The ' . $key . ' is invalid format.';
    } else if (strtolower($key) == 'email' && !filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
        $errors[$key][] = 'The ' . $key . ' is invalid format.';
    }
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'data' => [
            'messsage' => "Unprocessable Entity",
            'errors' => $errors
        ],
        'status' => 422
    ]);
} else {
    $productName = '';
    if (isset($_POST['tipe'])) {
        if (strtolower(trim($_POST['tipe'])) == 'daripromo') {
            $ambildata = "SELECT * FROM promos WHERE id='$_POST[promo_id]'";
            $queryambildata = mysqli_query($koneksi, $ambildata);
            while ($showambildata = mysqli_fetch_array($queryambildata)) {
                $productName = $showambildata['title_promo'];
            }
        } else if (strtolower(trim($_POST['tipe'])) == 'dariproduk') {
            $ambildata = "SELECT * FROM products WHERE id='$_POST[product_id]'";
            $queryambildata = mysqli_query($koneksi, $ambildata);
            while ($showambildata = mysqli_fetch_array($queryambildata)) {
                $productName = $showambildata['product_name'];
            }
        }
    }



    $nama_penerima = $_POST['name'];
    $email_penerima = $_POST['email'];
    $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['utm_source'] = "Pesta Online";
    $data['utm_campaign'] = $_POST['brand_product']; // Brand_id
    $data['utm_medium'] = $_POST['category_name']; // Category Product
    $data['utm_content'] = $productName; // Product Name
    $data['gclid'] = $_POST['hadiah'];


    // Include Body
    include "sendnotif.php";

    // Function Send Email
    function sendEmail($productName)
    {
        $nama_penerima = $_POST['name'];
        $email_penerima = $_POST['email'];

        $deskripsi = isset($_POST['product_id']) ? " Produk " : " Promo ";

        $email_pengirim = 'no-reply@pestahomecredit.com';  //no-reply@pestahomecredit.com   pestaconfiguration@gmail.com
        $password = 'noreply@p3st4'; //noreply@p3st4  HC1@p3st4
        $mail = new PHPMailer;
        $mail->SMTPDebug  = 0; // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = 'srv115.niagahoster.com'; //Set the SMTP server to send through smtp.gmail.com     srv115.niagahoster.com
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = $email_pengirim; //SMTP username
        $mail->Password   = $password; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        // $mail->SMTPSecure = 'ssl'; //gmail
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

    $method = "POST";
    // $url = "https://apixweb-dev.homecredit.co.id/api/v1/form/pesta-online";
    $url = "https://apixweb.homecredit.co.id/api/v1/form/pesta-online";
    $response = _curlPost($url, $data, $method);
    $result = json_encode($response);
    // echo json_decode($result);


    //Send Email Notification
    sendEmail($productName);

    // print_r($data);
    // exit;


    if (isset($_POST['tipe'])) {
        // echo strtolower(trim($_POST['tipe'])); exit;
        $promoID = 0; $productID = 0;
        if (strtolower(trim($_POST['tipe'])) == 'daripromo') {
            $ambilpromo_en = sekuriti($_POST['promo_id'], 'encrypt');
            $promoID = $_POST['promo_id'];
            // echo "<br />Dari Promo : " . $ambilpromo_en;
            // header('location:../../index.php?page=thankyouPagePromo&idpr=' . $ambilpromo_en);
            //     echo ("<script LANGUAGE='JavaScript'>
            // window.location.href='../../index.php?page=thankyouPagePromo&idpr=" . $ambilpromo_en . "';
            // </script>");
            $json_result = json_encode(['success' => true, 'redirect_uri' => 'index.php?page=thankyouPagePromo&idpr=' . $ambilpromo_en]);
        } else if (strtolower(trim($_POST['tipe'])) == 'dariproduk') {
            $ambilproduct_en = sekuriti($_POST['product_id'], 'encrypt');
            $productID = $_POST['product_id'];
            // echo "<br />Dari Product : " . $ambilproduct_en;
            // echo $ambilproduct_en;exit;
            // header('location:../../index.php?page=thankyouPage&idpr=' . $ambilproduct_en);
            $json_result = json_encode(['success' => true, 'redirect_uri' => 'index.php?page=thankyouPage&idpr=' . $ambilproduct_en]);
            // echo ("<script LANGUAGE='JavaScript'>
            // window.location.href='../../index.php?page=thankyouPage&idpr=" . $ambilproduct_en . "';
            // </script>");
        }

        $cektraffic = "SELECT * FROM traffic_buying WHERE ipaddress='$_SERVER[REMOTE_ADDR]' AND (product_id = $productID OR promo_id = $promoID) AND DATE(date_buy)=CURDATE()";
        
        $querycektraffic = mysqli_query($koneksi, $cektraffic);
        $NumberofTraffic = mysqli_num_rows($querycektraffic);
        
        if ($NumberofTraffic < 1) {
            $insertTraffic = "INSERT INTO traffic_buying (ipaddress, product_id, promo_id, date_buy) VALUES ('$_SERVER[REMOTE_ADDR]', '$productID', '$promoID', '" . date("Y-m-d H:i:s") . "')";
            mysqli_query($koneksi, $insertTraffic);
        }

        http_response_code(200);
        echo $json_result;
    }
}
