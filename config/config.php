<?php 
$databaseHost = 'localhost';
$databaseName = 'dbpesta_hci';
$databaseUsername = 'root';
$databasePassword = '';

// $databaseHost = 'localhost';
// $databaseName = 'pesta_hci';
// $databaseUsername = 'usr_prod';
// $databasePassword = 'db@hc!k3c3';

$koneksi = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}

// Funsi Date
function cleanTgl($tanggal)
{
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}


function _curlPost($url,$data=[], $method = "POST") {
	$curl = curl_init();
	$headers = array(
		// Set here requred headers
		"accept: application/json",
		// "accept-language: en-US,en;q=0.8",
		// "content-type: application/json",
	);
	$authorization = "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJmb3IiLCJuYW1lIjoicGVzdGFvbmxpbmUiLCJpYXQiOjE2Mzc4Mjc3MjJ9.ELrjMNUE7nQBvpY8Bm7BV1nxnnoUxirvxXoWZ_d7Urk";
	if($authorization) array_push($headers, $authorization);
	$request = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30000,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $method,
		// CURLOPT_POSTFIELDS => $data,
		// CURLOPT_POSTFIELDS => json_encode($data),
		// CURLOPT_HTTPHEADER => $headers,
	);
	if(isset($data['form_file']) && $data['form_file']) $request[CURLOPT_POSTFIELDS] = $data;
	else {
		array_push($headers, "content-type: application/json");
		$request[CURLOPT_POSTFIELDS] = json_encode($data);
	}

	$request[CURLOPT_HTTPHEADER] = $headers;
	// if($method == "POST") $request[CURLOPT_POSTFIELDS] = $data;
	// else $request[CURLOPT_POSTFIELDS] = json_encode($data);
	// return json_encode($data);
	curl_setopt_array($curl, $request);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);  
	return $response;
}

function sekuriti($string, $action = 'encrypt')
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'EI<44K4NNM4JUK3D3P4N'; // user define private key AA74CDCC2BBRT935136HH7B63C27
    $secret_iv = 'Hc!C0d3'; // user define secret key 5fgf5HJ5g27
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
?>