<?php 
$databaseHost = 'localhost';
$databaseName = 'dbpesta_hci';
$databaseUsername = 'root';
$databasePassword = '';

// $databaseHost = 'localhost';
// $databaseName = 'souq2557_dbsoulofjakarta';
// $databaseUsername = 'souq2557_souja';
// $databasePassword = 'lantai13';

$koneksi = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>