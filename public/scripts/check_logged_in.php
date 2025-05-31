<?php

session_start();

// Check if the user is remember on the browser
if (isset($_COOKIE['remember_me_token'])) {
    // A "Remember Me" cookie exists.
    $token = $_COOKIE['remember_me_token'];

    $_SESSION['username'] = $token;
}
else {
    if (!isset($_SESSION['username'])) {
        // User is not authenticated, redirect to login page or display an error message
        header('Location: /login.php');
        exit;
    }
}

?>