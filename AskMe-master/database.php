<?php
	// Connect to MySQL database news via php user
	$mysqli = new mysqli('localhost', 'archhacker', 'archhacker', 'ASKME');
	//$mysqli = new mysqli('localhost', 'phpuser', 'news', 'news');
	 
	if($mysqli->connect_errno) {
		printf("Connection Failed: %s\n", $mysqli->connect_error);
		exit;
	}
?>