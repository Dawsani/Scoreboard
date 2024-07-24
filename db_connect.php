<?php

session_start();

$currentFile = basename($_SERVER['PHP_SELF']);

if ($currentFile !== "sign_up.php" && $currentFile !== "login.php") {
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}
}

$server="localhost";
$username="root";
$password="parade";
$database="scoreboard";

// Connect to the database
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	echo "Connection to database failed.<br>";
}

?>

