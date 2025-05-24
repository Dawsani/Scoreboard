<!DOCTYPE html>

<?php
include 'db_connect.php'; 
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
