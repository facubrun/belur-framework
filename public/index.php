<?php

require_once  '../vendor/autoload.php';

use Belur\App;

$app = App::bootstrap(dirname(__DIR__))->run();
