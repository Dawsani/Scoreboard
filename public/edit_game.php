<?php include 'db_connect.php' ?>

<?php 
$gameId = $_GET['gameId'];
$scoreboardId = gameToScoreboard($conn, $gameId);
?>



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Game</title>
</head>
<body>

<?php include("header.php") ?>
<h1>Edit Game</h1>

<form action="edit_game.php?gameId=<?php echo $gameId ?>" method="post">
<table id='input-container'>
<tr>
	<th></th>
	<th>Player Name</th>
	<th>Deck Name</th>
</tr>
<?php

// Get all entries for the game
$sql = "SELECT player.name AS player_name, deck.name AS deck_name
	FROM game_entry
	JOIN player ON player_id = player.id
	JOIN deck ON deck_id = deck.id
	WHERE game_id = $gameId;";
$result = $conn->query($sql);

$counter = 1;
while ($row = $result->fetch_assoc()) {
	$playerName = $row['player_name'];
	$deckName = $row['deck_name'];

	echo "
	
	<tr id='input-group'>
	<th><label>Player $counter:</label></th>
	<th><input type='text' id='player-$counter-name' name='players[]' value='$playerName' required></th>
	<th><input type='text' id='player-$counter-deck' name='decks[]' value='$deckName' required</th> 
	</tr>

	";

	$counter += 1;
}

?>
</table>

<button onclick="addPlayerField()">Add Player</button><br><br>

<?php
$sql = "SELECT player.name AS winner_name, note
	FROM game
	JOIN player ON winner_id = player.id
	WHERE game.id = $gameId;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$winnerName = $row['winner_name'];
$note = $row['note'];

?>

<label for="winner">Winner:</label>
<input type="text" id="winner" name="winner" value='<?php echo $winnerName ?>'><br><br>

<label for="note">Note:</label>
<textarea rows=6 cols=50 id='note' name='note'><?php echo $note ?></textarea><br><br>

<button type="Submit">Submit</button>

</form>

<?php

$sql = "SELECT COUNT(*) AS num_players
	FROM game_entry
	WHERE game_id = $gameId;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$numPlayers = $row['num_players'];

?>

<script>

let inputContainer = document.getElementById('input-container');
let inputCount = <?php echo $numPlayers ?>;

function addPlayerField() {
	inputCount++;
	let newInputGroup = document.createElement('tr');
	newInputGroup.className = 'input-group';
	newInputGroup.innerHTML = `
	<th><label>Player ${inputCount}</label></th>
	<th><input type="text" id="input-${inputCount}-name" name="players[]" required></th>
	<th><input type="text" id="input-${inputCount}-deck" name="decks[]" required></th>`;
	inputContainer.appendChild(newInputGroup);
}
</script>

</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$playerInputs = $_POST['players'];
	$deckInputs = $_POST['decks'];
	$winner = $_POST['winner'];
	$winnerId = playerNameToId($conn, $scoreboardId, $winner);

	// Make sure the winner was one of the players
	if (!in_array($winner, $playerInputs)) {
		echo "The submitted winner \"$winner\" must be a player in the game.<br>";
		exit();
	}

	// Add each new player to the player database
	foreach ($playerInputs as $player) {
		$sql = "SELECT * FROM player WHERE name LIKE '$player';";
		$result = $conn->query($sql);
		
		// If the player does not exist, create it.
		if ($result->num_rows === 0) {
			$sql = "INSERT INTO player (name) VALUES ('$player');";
			$conn->query($sql);
		}
	}

	// Add each new deck to the deck table
	foreach ($deckInputs as $deck) {
		$sql = "SELECT * FROM deck WHERE name LIKE '$deck';";
		$result = $conn->query($sql);

		// If the deck does not exist, create it
		if ($result->num_rows === 0) {
			$sql = "INSERT INTO deck (name, scoreboard_id) VALUES ('$deck', $scoreboardId);";
			$conn->query($sql);
		}
	}

	// Check if a note was recorded
	$note = $_POST['note'];
	if ($note == '') {
		$note = NULL;
	}

	// Update the game record
	$sql = "UPDATE game
		SET winner_id = $winnerId
		WHERE id = $gameId;";
	$conn->query($sql);

	$stmt = $conn->prepare("UPDATE game
				SET note = ?
				WHERE id = ?;");
	$stmt->bind_param('si', $note, $gameId);
	$stmt->execute();

	// Update the entries for the game
	// first remove all entries for this game
	$sql = "DELETE FROM game_entry
		WHERE game_id = $gameId;";
	$conn->query($sql);

	for ($i = 0; $i < count($playerInputs); $i++) {
		$player = $playerInputs[$i];
		$playerId = playerNameToId($conn, $scoreboardId, $player);

		$deck = $deckInputs[$i];
		$deckId = deckNameToId($conn, $scoreboardId, $deck);

		$sql = "INSERT INTO game_entry (game_id, player_id, deck_id) VALUES ($gameId, $playerId, $deckId);";
		$conn->query($sql);
	}

	echo "Game recorded succesfully.<br>";
	header("location: scoreboard.php?scoreboardId=$scoreboardId");
}
?>
