<?php
define('SF_PATH', dirname(__DIR__));
require_once (SF_PATH . '/vendor/autoload.php');

$application = new sf\web\Application();
$application->run();
