<?php

use Core\Router;
use Core\Session;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/bootstrap/autoload.php';

load_env(BASE_PATH . '/.env');

$GLOBALS['config'] = [
    'app' => require BASE_PATH . '/config/app.php',
    'database' => require BASE_PATH . '/config/database.php',
    'constants' => require BASE_PATH . '/config/constants.php',
];

Session::start();

$router = new Router();
require BASE_PATH . '/routes/web.php';

return $router;
