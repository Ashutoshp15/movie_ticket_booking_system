<?php

$host = "database-1.cnmy5rtnio8w.us-east-1.rds.amazonaws.com";
$dbuser = "admin";
$dbpass = "password";
$db = "movie_booking";

$conn = mysqli_connect($host, $dbuser, $dbpass, $db);

if(mysqli_connect_errno($conn)) {
	echo "ERROR: ".mysqli_error($conn);
}
?>
