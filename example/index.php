<?php
use DarkNet\Speed;

require 'vendor/autoload.php';

$speedTest = new Speed('https://www.google.com/');
$speedTest->setPingUrl('https://www.google.com');
$ping = $speedTest->getPing();
$downloadSpeed = $speedTest->getDownloadSpeed('MB');
$uploadSpeed = $speedTest->getUploadSpeed('GB');

if ($ping !== false) {
    echo "Ping: {$ping} ms\n";
} else {
    echo "Unable to ping server.\n";
}
echo "Download speed: {$downloadSpeed}\n";
echo "Upload speed: {$uploadSpeed}\n";