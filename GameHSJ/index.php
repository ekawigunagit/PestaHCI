<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Hurry Space Jam</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, viewport-fit=cover">

	<meta name="generator" content="Construct 3">

	<link rel="manifest" href="appmanifest.json">
	<link rel="apple-touch-icon" sizes="180x170" href="icons/icon-16.png">
	<link rel="icon" type="image/png" href="icons/icon-16.png">
	<style>
		/* @media(min-width:1024px) {
			canvas {
				overflow: none !important;
				height: 100% !important;
				padding: 0 !important;
				margin: 0 !important;
			}
		}

		@media(max-width:576px) {
			canvas {
				overflow: none !important;
				width: 100% !important;
				height: 100% !important;
				padding: 0 !important;
				margin: 0 !important;
			}
		}

		@media(max-width:576px) {
			input {
				position: absolute !important;
				top: 165px !important;
				padding: 0 !important;
				margin: 0 !important;
			}
		} */
	</style>


</head>

<body>

	<div id="fb-root"></div>

	<noscript>
		<div id="notSupportedWrap">
			<h2 id="notSupportedTitle">This content requires JavaScript</h2>
			<p class="notSupportedMessage">JavaScript appears to be disabled. Please enable it to view this content.</p>
		</div>
	</noscript>
	<script src="scripts/supportcheck.js?t=<?php echo date("S"); ?>"></script>
	<script src="scripts/offlineclient.js?t=<?php echo date("S"); ?>" type="module"></script>
	<script src="scripts/main.js?t=<?php echo date("S"); ?>" type="module"></script>
	<script src="scripts/register-sw.js?t=<?php echo date("S"); ?>" type="module"></script>
</body>

</html>