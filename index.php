<!DOCTYPE html>

<?php include 'db_connect.php' ?>

<html>
<head>
<title>MTG Scoreboard</title>
</head>
<body>

<h1>MTG Scoreboard</h1>

<a href="create_scoreboard.php">Create New Scoreboard</a>

<h2>Your Scoreboards</h2>
<?php

$username = $_SESSION['username'];

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
?>

</body>
</html>
