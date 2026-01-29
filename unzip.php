<?php
$zipFile = '26j.zip';  // Name of your zip file
$extractTo = __DIR__;  // Extract to current directory

$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo($extractTo);
    $zip->close();
    echo "Extraction successful!";
} else {
    echo "Failed to open the zip file.";
}
?>
