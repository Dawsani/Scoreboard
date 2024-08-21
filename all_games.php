<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>

<style>
table {
  border:1px solid black;
  border-collapse: collapse;
  padding: 10px
}

td, th {
  border: 1px solid #dddddd;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<title>
<?php
	$scoreboardId = $_GET['scoreboardId'];
	$sql = "SELECT name FROM scoreboard WHERE id = $scoreboardId;";
	$result = $conn->query($sql);
	$scoreboardName = $result->fetch_assoc()['name'];
	echo $scoreboardName;
?>
</title>
</head>

<body>

<?php include("header.php"); ?>
<h1><?php echo $scoreboardName ?></h1>

<h2>All Games</h2>

<table>
<tr>
	<th style="min-width: 75px">Date</th>
	<th style="min-width: 100px">Winner</th>
	<th style="min-width: 200px">Other Players</th>
	<th style="min-width: 400px">Note</th>
	<th>Edit Game</th>
</tr>

<?php
// Get all games
$sql = "SELECT created_at, name AS winner_name, winner_id, game.id AS game_id, note
	FROM game JOIN player ON game.winner_id = player.id
	WHERE game.scoreboard_id = $scoreboardId
	ORDER BY game.id DESC;";
$recentGames = $conn->query($sql);


if ($recentGames->num_rows > 0) {
	while ($row = $recentGames->fetch_assoc()) {
		$createdAt = convertDatetime($row['created_at']);
		$winnerId = $row['winner_id'];
		$winnerName = $row['winner_name'];
		$gameId = $row['game_id'];
		$note = $row['note'];

		$sql = "SELECT deck.name as winner_deck_name 
			FROM game 
			JOIN game_entry ON game.id = game_id
			JOIN deck ON deck.id = deck_id
			WHERE game_id = $gameId AND player_id = $winnerId;";
		$winnerDeckNameResult = $conn->query($sql);
		$winnerDeckName = $winnerDeckNameResult->fetch_assoc()['winner_deck_name'];

		// Get a list of every player in the game, except the winner
		$sql = "SELECT player.name as player_name, deck.name AS deck_name
			FROM game_entry join player on game_entry.player_id = player.id
			JOIN deck ON game_entry.deck_id = deck.id
			WHERE game_entry.game_id = $gameId AND player.name NOT LIKE '$winnerName'
			ORDER BY player.name;";
		
		$playerList = $conn->query($sql);
			
		echo "
		<tr>
			<td>$createdAt</td>
			<td>$winnerName using $winnerDeckName</td>
			<td>";

			$numPlayers = $playerList->num_rows;
			$playerCount = 1;
			while ($playerRow = $playerList->fetch_assoc()) {
				$player = $playerRow['player_name'];
				$deckName = $playerRow['deck_name'];
				echo "$player using $deckName";
				if ($playerCount < $numPlayers) {
					echo ",<br>";
				}
				$playerCount += 1;
			}	
			echo "</td>
			<td>$note</td>
			<td><a href='edit_game.php?gameId=$gameId'>Edit Game</a></td>
		</tr>";

	}
} else {
	echo "No games recorded.";
}
?>

</table>
</body>
</html>


