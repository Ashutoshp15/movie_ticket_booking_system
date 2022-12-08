<?php

$host = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "movie_booking";

$conn = mysqli_connect($host, $dbuser, $dbpass, $db);

if(mysqli_connect_errno($conn)) {
	echo "ERROR: ".mysqli_error($conn);
}
?>
