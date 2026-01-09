<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use Belur\App;

$app = App::bootstrap(dirname(__DIR__))->run();
