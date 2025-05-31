<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/load-config.php';
$configs = load_config(); 

$host = $configs['dbhost'];
$username = $configs['dbuser'];
$user_pass = $configs['dbpassword'];
$database_in_use = $configs['dbname'];

$mysqli = new mysqli($host, $username, $user_pass, $database_in_use);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
