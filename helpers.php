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
?>
