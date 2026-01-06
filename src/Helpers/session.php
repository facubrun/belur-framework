<?php

use Belur\Session\Session;

use function Belur\Helpers\app;

function session(): Session {
    return app()->session();
}
