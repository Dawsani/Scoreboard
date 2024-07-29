<!DOCTYPE html>

<?php include 'db_connect.php' ?>

<html>
<head>
<title>MTG Scoreboard</title>
</head>
<body>

<h1>MTG Scoreboard</h1>

<?php

$username = $_SESSION['username'];

$sql = "SELECT name
	FROM scoreboard
	WHERE id = (
		SELECT id
		FROM user
		WHERE user.name LIKE '$username'
	);";
$scoreboards = $conn->query($sql);

if ($scoreboards->num_rows > 0) {
	while ($row = $scoreboards->fetch_assoc()) {
		$scoreboardName = $row['name'];
		echo $scoreboardName;
	}
}
?>

</body>
</html>
