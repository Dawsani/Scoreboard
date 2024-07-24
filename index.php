<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<title>Scoreboard</title>
</head>

<body>

<h1>Game Recorder</h1>

<button onclick = "window.location.href = 'add_game.php'">Record Game</button><br><br>

<h2>Recent games</h2>

<?php
// Get the most recent 10 games
$sql = "SELECT created_at, name AS winner_name, game.id AS game_id
	FROM game JOIN player ON game.winner_id = player.id
	ORDER BY game.id DESC
	LIMIT 10";
$recentGames = $conn->query($sql);

if ($recentGames->num_rows > 0) {
	while ($row = $recentGames->fetch_assoc()) {
		$createdAt = $row['created_at'];
		$winnerName = $row['winner_name'];
		$gameId = $row['game_id'];

		// Get a list of every player in the game, except the winner
		$sql = "SELECT name
			FROM game_player join player on game_player.player_id = player.id
			WHERE game_player.game_id = $gameId AND name NOT LIKE '$winnerName'
			ORDER BY name;";
		$playerList = $conn->query($sql);

		echo $row['created_at'] . " - " . $winnerName . " won against ";
		
		$numPlayers = $playerList->num_rows;
		$playerCounter = 0;

		if ($numPlayers > 0) {
			while ($playerRow = $playerList->fetch_assoc()) {
				$playerName = $playerRow['name'];
				echo $playerName;

				if ($playerCounter === $numPlayers - 1) {
					echo ".";
				} else if ($playerCounter === $numPlayers - 2) {
					echo " and ";
				} else { 
					echo ", ";
				}
				$playerCounter += 1;
			}
		} else {
			echo "nobody.";
		}
		
		echo "<br>";
	}
}
else {
	echo "No games recorded.";
}
?>

<h2>Games Played</h2>
<?php
// Get total games played by each player
$sql = "select name, count(*) AS games_played from game_player join player on player_id = player.id group by name;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		echo $row['name'] . ": " . $row['games_played'] . "<br>";
	}
}
?>
</body>
</html>


