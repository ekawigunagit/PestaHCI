<?php 

// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'dbgame_hci';

// Api config
$RESET_KEY = 'YX17SkJSOCUUfFUmVXBrNF5xSEldQmokOmJXblleb3Y4RyRkPnM0fHx9UCgrJT5D';

// Cross-Origin Resource Sharing Header (disabled if you want to skip this)
$allowedOrigins = [
    "https://static-sg.theflavare.com/",
    "https://theflavare.com/"
];

// if (in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
// }

header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');

// Try and connect using the info above.
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to database: ' . mysqli_connect_error());
}
