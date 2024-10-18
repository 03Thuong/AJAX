<?php
	//Kết nối cơ sở dữ liệu
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qlsv";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
  		die("Connection failed: " . $conn->connect_error);
	}
?>