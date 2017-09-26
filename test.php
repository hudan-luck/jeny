<?php
//include 'phpqrcode.php';
//QRcode::png('http://www.baidu.com/');
require 'vendor/autoload.php';
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
\PHPQRCode\QRcode::png("Test", "logo1.png", 'L', 4, 2);
?>
