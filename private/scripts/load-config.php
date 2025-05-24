<?php
// app/load_config.php

function load_config(): array {
    $default = require $_SERVER['DOCUMENT_ROOT'] . '/../private/config/config.php';
    $localPath = $_SERVER['DOCUMENT_ROOT'] . '/../private/config/config.local.php';
    if (file_exists($localPath)) {
        $local = require $localPath;
        return $local;
    }

    return $default;
}

?>