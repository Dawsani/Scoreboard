<?php
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
