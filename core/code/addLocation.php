<?php
include "../../config/config.php";

$txtcity = $_POST['location_city'];
$txtarea = $_POST['location_area'];
$txtip = $_SERVER['REMOTE_ADDR'];

$addLocation="INSERT INTO temp_locations (ip_visitor, province_name, districts_name) values ('$txtip', '$txtcity','$txtarea')";
mysqli_query($koneksi, $addLocation);

header('location:../../index.php');
?>