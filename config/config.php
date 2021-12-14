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
$nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
    	                "Juni", "Juli", "Agustus", "September", 
        	            "Oktober", "November", "Desember");
?>