<?php

use Core\Request;

$router = require dirname(__DIR__) . '/bootstrap/app.php';
$router->dispatch(new Request());
