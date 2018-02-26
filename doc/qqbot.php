<?php
include __DIR__ . '/../vendor/autoload.php';

use Slince\Tuzki\Tuzki;

date_default_timezone_set('Prc');

$qqTuzki = new Tuzki(getcwd() . '/qrcode.png');
$qqTuzki->listen();