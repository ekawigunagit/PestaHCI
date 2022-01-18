<?php
	error_reporting(0);
	include "config/config.php";

	$cekvisitors = "SELECT * FROM unique_visitors WHERE ipaddress='$_SERVER[REMOTE_ADDR]' AND DATE(date_visit)=CURDATE()";
	$querycekvisitors = mysqli_query($koneksi, $cekvisitors);
	$NumberofVisitor = mysqli_num_rows($querycekvisitors);

	if ($NumberofVisitor<1){
		$insertVisitor = "INSERT INTO unique_visitors (ipaddress, date_visit) VALUES ('$_SERVER[REMOTE_ADDR]', '".date("Y-m-d H:i:s")."')";
		mysqli_query($koneksi, $insertVisitor);
	}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'core/page/head.php'; ?>
</head>

<body>

<?php //include 'test.php'; ?>

		<!-- Top Menu -->
		<?php include 'core/menu/topmenu.php'; ?>
		
		<!-- Home Page -->

		<?php if(!isset($_GET['page'])): include 'core/menu/homeslider.php'; endif; ?>

		<!-- Home Page -->
		<?php include 'core/code/route.php'; ?>

		<!-- Footer Area -->
		<?php include 'core/page/footer.php'; ?>

	
	<?php include 'core/code/jsscript.php'; ?>

</body>
</html>