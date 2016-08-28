<?php
include __DIR__ . '/../vendor/autoload.php';

use Slince\Tuzki\Tuzki;
use Slince\Tuzki\Cogitation\ItpkCogitation;
use Slince\Tuzki\Cogitation\TulingCogitation;

$tuzki = new Tuzki(new ItpkCogitation('27bad0c963b9f4a460dd5e1cb6ad76b0', 'vkq6b8qjot7j'));

$answer = $tuzki->listen('Hello')->answer();
echo $answer;

echo PHP_EOL;

$key = 'xxx'; //图灵机器人key，可以去官网自行申请
$tuzki->setCogitation(new TulingCogitation($key));

echo $tuzki->listen('Hello')->answer();