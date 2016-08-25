<?php
include __DIR__ . '/../vendor/autoload.php';

use Slince\Tuzki\QQTuzki;

date_default_timezone_set('Prc');

$qqTuzki = new QQTuzki(getcwd() . '/qrcode.png');
$qqTuzki->listen();