<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/scripts/check_logged_in.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/load-config.php';
    
    $config = load_config();

    $baseDir = realpath($config['datadirectory']); // Canonical path

    $requestedPath = $_GET['src'];
    $fullPath = realpath($baseDir . '/' . $requestedPath);

    // Validate that real path is inside the allowed base directory
    if ($fullPath === false || strpos($fullPath, $baseDir) !== 0) {
        http_response_code(403);
        exit('Access denied.');
    }

    if (!file_exists($fullPath)) {
        http_response_code(404);
        exit('Image not found.');
    }

    // Determine content type (optional: based on file extension)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $fullPath);
    finfo_close($finfo);

    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($fullPath));
    readfile($fullPath);
    exit;
?>