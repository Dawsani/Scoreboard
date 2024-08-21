<header>

<meta name="viewport" content="width=device-width, initial-scale=1">

<a href="logout.php">Log Out</a>
<a href="index.php">Home</a>
<?php
	if (isset($_GET['scoreboardId'])) {

		$scoreboardId = $_GET['scoreboardId'];
		echo "<a href='scoreboard.php?scoreboardId=$scoreboardId'>Scoreboard</a>";
	}
	else if (isset($_GET['gameId'])) {
		$gameId = $_GET['gameId'];
		$scoreboardId = gameToScoreboard($conn, $gameId);
		echo "<a href='scoreboard.php?scoreboardId=$scoreboardId'>Scoreboard</a>";
	}
?>
</header>

