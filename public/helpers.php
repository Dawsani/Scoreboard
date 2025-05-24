<?php

function getAllDecks($conn, $scoreboardId) {
	$decks = array();
	$sql = "SELECT id
		FROM deck
		WHERE scoreboard_id = $scoreboardId;";
	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		return $decks;
	}
	else {
		while ($row = $result->fetch_assoc()) {
			$deckId = $row['id'];
			array_push($decks, $deckId);
		}
	}

	return $decks;
}


function getAllPlayers($conn, $scoreboardId) {
	$players = array();
	$sql = "SELECT id
		FROM player
		WHERE scoreboard_id = $scoreboardId;";
	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		return $players;
	}
	else {
		while ($row = $result->fetch_assoc()) {
			$playerId = $row['id'];
			array_push($players, $playerId);
		}
	}

	return $players;
}

function getScoreboardIdFromPlayerId($conn, $playerId) {
	$sql = "SELECT scoreboard_id
		FROM player
		WHERE id = $playerId;";
	$result = $conn->query($sql);
	$scoreboardId = $result->fetch_assoc()['scoreboard_id'];
	return $scoreboardId;
}

function getScoreboardIdFromDeckId($conn, $deckId) {
	$sql = "SELECT scoreboard_id
		FROM deck
		WHERE id = $deckId;";
	$result = $conn->query($sql);
	$scoreboardId = $result->fetch_assoc()['scoreboard_id'];
	return $scoreboardId;
}

function getDeckWinrateWithPlayerCount($conn, $deckId, $playerCount) {
	$scoreboardId = getScoreboardIdFromDeckId($conn, $deckId);
	
	$sql = "
	SELECT COALESCE(games_won, 0) AS games_won, games_played, COALESCE(games_won, 0) / games_played * 100 AS win_rate
	FROM
	(
	SELECT COUNT(*) AS games_played, deck_id
	FROM game_entry
	JOIN (
		SELECT game_id, COUNT(*) AS num_players
		FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		WHERE game.scoreboard_id = $scoreboardId
		GROUP BY game_id
	) AS game_players
	ON game_entry.game_id = game_players.game_id
	JOIN deck ON deck.id = game_entry.deck_id
	WHERE num_players = $playerCount
	GROUP BY deck_id
	) AS games_played

	LEFT JOIN

	(
	SELECT COUNT(*) AS games_won, deck_id
	FROM game
	JOIN game_entry ON game.id = game_id
	JOIN (
		SELECT game_id, COUNT(*) AS num_players
		FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		WHERE game.scoreboard_id = $scoreboardId
		GROUP BY game_id
	) AS game_players
	ON game.id = game_players.game_id
	WHERE num_players = $playerCount AND game.winner_id = game_entry.player_id
	GROUP BY deck_id
	) AS games_won
	ON games_played.deck_id = games_won.deck_id
	WHERE games_played.deck_id = $deckId;";
	
	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		return "(0/0) N/A";
	}

	$result = $result->fetch_assoc();
	$winRate = $result['win_rate'];
	$gamesPlayed = $result['games_played'];
	$gamesWon = $result['games_won'];
	
	$winRateString = number_format((float)$winRate, 2, '.', ',');
	$resultString = "($gamesWon/$gamesPlayed) $winRateString%";

	return $resultString;
}


function getPlayerWinrateWithPlayerCount($conn, $playerId, $playerCount) {
	$scoreboardId = getScoreboardIdFromPlayerId($conn, $playerId);
	
	$sql = "
	SELECT COALESCE(games_won, 0) AS games_won, games_played, COALESCE(games_won, 0) / games_played * 100 AS win_rate
	FROM
	(
	SELECT COUNT(*) AS games_played, player_id
	FROM game_entry
	JOIN (
		SELECT game_id, COUNT(*) AS num_players
		FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		WHERE game.scoreboard_id = $scoreboardId
		GROUP BY game_id
	) AS game_players
	ON game_entry.game_id = game_players.game_id
	JOIN player ON player.id = game_entry.player_id
	WHERE num_players = $playerCount
	GROUP BY player_id
	) AS games_played

	LEFT JOIN

	(
	SELECT COUNT(*) AS games_won, winner_id AS  player_id
	FROM game
	JOIN (
		SELECT game_id, COUNT(*) AS num_players
		FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		WHERE game.scoreboard_id = $scoreboardId
		GROUP BY game_id
	) AS game_players
	ON game.id = game_players.game_id
	JOIN player ON player.id = game.winner_id
	WHERE num_players = $playerCount
	GROUP BY winner_id
	) AS games_won
	ON games_played.player_id = games_won.player_id
	WHERE games_played.player_id = $playerId
	ORDER BY win_rate DESC;";
	
	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		return "(0/0) N/A";
	}

	$result = $result->fetch_assoc();
	$winRate = $result['win_rate'];
	$gamesPlayed = $result['games_played'];
	$gamesWon = $result['games_won'];
	
	$winRateString = number_format((float)$winRate, 2, '.', ',');
	$resultString = "($gamesWon/$gamesPlayed) $winRateString%";

	return $resultString;
}

function convertDatetime($datetime) {
	$array = explode(' ', $datetime);
	$date = $array[0];
	$time = $array[1];

	$array = explode('-', $date);
	$year = $array[0];
	$month = (int)$array[1];
	$day = (int)$array[2];

	$array = explode(':', $time);
	$hour = $array[0];
	$minute = $array[1];
	$second = $array[2];

	$datetime = "";
	$monthString = "";
	if ($month == 1) {
		$monthString = "January";
	} 
	else if ($month == 2) {
		$monthString = "February";
	}
	else if ($month == 3) {
		$monthString = "March";
	}
	else if ($month == 4) {
		$monthString = "April";
	}
	else if ($month == 5) {
		$monthString = "May";
	}
	else if ($month == 6) {
		$monthString = "June";
	}
	else if ($month == 7) {
		$monthString = "July";
	}
	else if ($month == 8) {
		$monthString = "August";
	}
	else if ($month == 9) {
		$monthString = "September";
	}
	else if ($month == 10) {
		$monthString = "October";
	}
	else if ($month == 11) {
		$monthString = "November";
	}
	else if ($month == 12) {
		$monthString = "December";
	}
	else {
		$monthString = $month;
	}

	$dateSuffix = "th";
	$lastDigitOfDate = (int)substr($day, -1);
	if ($lastDigitOfDate == 1) {
		$dateSuffix = "st";
	} else if ($lastDigitOfDate == 2) {
		$dateSuffix = "nd";
	} else if ($lastDigitOfDate == 3) {
		$dateSuffix = "rd";
	}

	$meridiem = "AM";
	if ((int)$hour > 12) {
		$meridiem = "PM";
		$hour = (string)((int)$hour - 12);
	}

	$datetime = "$monthString $day$dateSuffix, $year $hour:$minute $meridiem";

	return $datetime;
}

function checkScoreboardAccess($conn, $username, $scoreboardId) {
	$userId = usernameToId($conn, $username);

	$sql = "SELECT * 
		FROM scoreboard JOIN scoreboard_user 
		WHERE (owner_id = $userId OR user_id = $userId) AND id=$scoreboardId;";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		return true;
	}
	else {
		return false;
	}
}

function gameToScoreboard($conn, $gameId) {
	$sql = "SELECT scoreboard_id
		FROM game
		WHERE game.id = $gameId;";
	$result = $conn->query($sql);
	$scoreboardId = $result->fetch_assoc()['scoreboard_id'];
	return $scoreboardId;
}

function deckNameToId($conn, $scoreboardId, $deckName) {
	$stmt = $conn->prepare("SELECT id FROM deck WHERE name = BINARY ? AND scoreboard_id = ?;");
	$stmt->bind_param('si', $deckName, $scoreboardId);
	$stmt->execute();

	$deckId = -1;

	$stmt->store_result();
	$stmt->bind_result($deckId);
	$stmt->fetch();

	return $deckId;
}

function deckIdToName($conn, $deckId) {
	$sql = "SELECT deck.name AS deck_name
		FROM deck
		WHERE id = $deckId;";
	$result = $conn->query($sql);
	$playerName = $result->fetch_assoc()['deck_name'];
	return $playerName;
}


function playerIdToName($conn, $playerId) {
	$sql = "SELECT player.name AS player_name
		FROM player
		WHERE id = $playerId;";
	$result = $conn->query($sql);
	$playerName = $result->fetch_assoc()['player_name'];
	return $playerName;
}

function playerNameToId($conn, $scoreboardId, $playerName) {
	$stmt = $conn->prepare("SELECT id FROM player WHERE name LIKE ? AND scoreboard_id = ?;");
	$stmt->bind_param('si', $playerName, $scoreboardId);
	$stmt->execute();

	$playerId = -1;

	$stmt->store_result();
	$stmt->bind_result($playerId);
	$stmt->fetch();

	return $playerId;
}

function usernameToId($conn, $username) {
	$sql = "SELECT id FROM user WHERE name LIKE '$username';";
	$result = $conn->query($sql);

	if ($result->num_rows === 1) {
		return $result->fetch_assoc()['id'];
	} else {
		return -1;
	}
}

function scoreboardIdToName($conn, $scoreboardId) {
	$sql = "SELECT name FROM scoreboard WHERE id = $scoreboardId;";
	$result = $conn->query($sql);
	if (!$result) {
		return -1;
	}
	
	if ($result->num_rows == 0) {
		return -1;
	}

	$scoreboardName = $result->fetch_assoc()['name'];
	return $scoreboardName;
}
