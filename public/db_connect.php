<?php
include "helpers.php";

session_start();

$currentFile = basename($_SERVER['PHP_SELF']);

// Send non-signed in users to the sign in page
if ($currentFile !== "sign_up.php" && $currentFile !== "login.php") {
	if (!isset($_SESSION['username'])) {
		if (isset($_COOKIE['username'])) {
			$_SESSION['username'] = $_COOKIE['username'];
		}
		else {
			header("Location: login.php");
			exit();
		}
	}
}

require $_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/load-config.php";
$config = load_config();

$server = $config['dbhost'];
$username = $config['dbuser'];
$password = $config['dbpassword'];
$database = $config['dbname'];

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
		
	if (!checkScoreboardAccess($conn, $username, $scoreboardId)) {
		header('location: scoreboard_no_access.php');
		exit();
	}
}

if (isset($_GET['gameId'])) {
	$gameId = $_GET['gameId'];
	$scoreboardId = gameToScoreboard($conn, $gameId);
	$username = $_SESSION['username'];

	if (!checkScoreboardAccess($conn, $username, $scoreboardId)) {
                header('location: scoreboard_no_access.php');
                exit();
        }
}
?>

