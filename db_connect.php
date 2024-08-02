<?php
include "helpers.php";

session_start();

$currentFile = basename($_SERVER['PHP_SELF']);

// Send non-signed in users to the sign in page
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

// If the page corresponds to a specific scoreboard, make sure that user has access to the scoreboard

if (isset($_GET['scoreboardId'])) {

	$scoreboardId = $_GET['scoreboardId'];
	$username = $_SESSION['username'];
	$userId = usernameToId($conn, $username);

	$hasAccess = 0;
	$sql = "SELECT * 
		FROM scoreboard JOIN scoreboard_user 
		WHERE (owner_id = $userId OR user_id = $userId) AND id=$scoreboardId;";
	$result = $conn->query($sql);
	
	if ($result->num_rows == 0) {
		header('location: scoreboard_no_access.php');
		exit();
	}
}
?>

