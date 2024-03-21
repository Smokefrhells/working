<?php

use App\Router;

require_once 'vendor/autoload.php';

(new Router([
    'ip' => $_SERVER['REMOTE_ADDR'],
    'uri' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
]))->renderResponse();

exit;