<?php
include __DIR__ . '/../vendor/autoload.php';

use Slince\Tuzki\QQTuzki;

$qqTuzki = new QQTuzki(getcwd() . '/qrcode.png');
$qqTuzki->listen();