<!DOCTYPE html>

<?php
include 'db_connect.php'; 
?>

<html>
<head>
<title>MTG Scoreboard</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>MTG Scoreboard</h1>

<a href="create_scoreboard.php">Create New Scoreboard</a>

<h2>Your Scoreboards</h2>
<?php

$username = $_SESSION['username'];
$userId = usernameToId($conn, $username);

$sql = "SELECT id, name
	FROM scoreboard
	WHERE owner_id = (
		SELECT id
		FROM user
	WHERE user.name LIKE '$username'
	);";
$scoreboards = $conn->query($sql);

if ($scoreboards->num_rows > 0) {
	while ($row = $scoreboards->fetch_assoc()) {
		$scoreboardName = $row['name'];
		$scoreboardId = $row['id'];
		echo "<a href='scoreboard.php?scoreboardId=$scoreboardId'>$scoreboardName</a><br>";
	}
}


$sql = "SELECT scoreboard_id, scoreboard.name
	FROM scoreboard JOIN scoreboard_user ON scoreboard.id = scoreboard_id
	WHERE user_id = $userId;";
$scoreboards = $conn->query($sql);

if ($scoreboards->num_rows > 0) {
	while ($row = $scoreboards->fetch_assoc()) {
		$scoreboardName = $row['name'];
		$scoreboardId = $row['scoreboard_id'];
		echo "<a href='scoreboard.php?scoreboardId=$scoreboardId'>$scoreboardName</a><br>";
	}
}

?>

</body>
</html>
