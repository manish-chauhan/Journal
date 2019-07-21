<?php

namespace bin;

$env = require __DIR__ . '/.env.php';
$container = require __DIR__ . '/../vendor/autoload.php';
$constants = require __DIR__ . '/contants.php';

use Symfony\Component\Console\Application;

$console = new Application('AmbitionBox Journal', '1.0.0');

$console->addCommands(array(
    new \bin\command\testCommand(),
    new \bin\command\CreateUserCommand(),
    new \bin\command\LoginUserCommand()
));

$console->run();
