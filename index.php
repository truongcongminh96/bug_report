<?php
declare(strict_types=1);

use App\Helpers\Config;

require_once __DIR__ . '\vendor\autoload.php';

$config = Config::get('app', 'app_name');
var_dump($config);
