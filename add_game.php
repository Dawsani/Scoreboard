<?php include 'db_connect.php' ?>

<?php $scoreboardId = $_GET['scoreboardId']; ?>



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Record Game</title>
</head>
<body>

<?php include("header.php") ?>
<h1>Record Game</h1>

<form action="add_game.php?scoreboardId=<?php echo $scoreboardId ?>" method="post">
<table id='input-container'>
<tr>
	<th></th>
	<th>Player Name</th>
	<th>Deck Name</th>
</tr>
<tr id='input-group'>
	<th><label>Player 1:</label></th>
	<th><input type="text" id="player-1-name" name="players[]" required></th>
	<th><input type="text" id="player-1-deck" name="decks[]" required></th>
</tr>
</table>

<button onclick="addPlayerField()">Add Player</button><br><br>

<label for="winner">Winner:</label>
<input type="text" id="winner" name="winner"><br><br>

<label for="note">Note:</label>
<textarea rows=6 cols=50 id='note' name='note'></textarea><br><br>

<button type="Submit">Submit</button>

</form>

<script>

let inputContainer = document.getElementById('input-container');
let inputCount = 1;

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
			$stmt = $conn->prepare("INSERT INTO player (name, scoreboard_id) VALUES (?, ?);");
			$stmt->bind_param('si', $player, $scoreboardId);
			$stmt->execute();
		}
	}

	// Add each new deck to the deck table
	foreach ($deckInputs as $deck) {
		$sql = "SELECT * FROM deck WHERE LOWER(name) LIKE LOWER('$deck');";
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

	// Create the game record
	$sql = $conn->prepare("
				INSERT INTO game (winner_id, scoreboard_id, note) 
				VALUES (?, ?, ?)"
		);
	$sql->bind_param('iis', $winnerId, $scoreboardId, $note);
	$sql->execute();

	// Get the id of the newest record
	$gameId = $conn->insert_id;

	// Add the records to the game
	for ($i = 0; $i < count($playerInputs); $i++) {
		$player = $playerInputs[$i];
		$deck = $deckInputs[$i];
		$playerId = playerNameToId($conn, $scoreboardId, $player);
		$deckId = deckNameToId($conn, $scoreboardId, $deck);

		$sql = "INSERT INTO game_entry (game_id, player_id, deck_id)
			VALUES ($gameId, $playerId, $deckId);";
		echo $sql;
		$conn->query($sql);
	}

	echo "Game recorded succesfully.<br>";
	header("location: scoreboard.php?scoreboardId=$scoreboardId");
}
?>
