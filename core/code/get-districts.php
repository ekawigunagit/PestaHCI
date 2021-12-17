<?php
include "../../config/config.php";
$header = $_SERVER['HTTP_TOKEN'];

if($header == $token) {
    $province_id = $_GET['id'];
    $data_district = "SELECT * FROM districts LEFT JOIN provinces ON districts.province_id = provinces.id WHERE districts.status=1 AND provinces.province_name = '".$province_id."' ORDER BY districts.districts_name ASC";
    $query_district = mysqli_query($koneksi, $data_district);

    $results = [];
    while ($show_district = mysqli_fetch_array($query_district)) {
        $results[] = [
            'district_name' => $show_district['districts_name'],
            'id' => $show_district['id'],
        ];
    }

    echo json_encode($results);
}
else echo json_encode(['error' => 'Invalid token']);
?>