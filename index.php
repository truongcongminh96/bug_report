<?php
declare(strict_types=1);

require_once __DIR__ . '\vendor\autoload.php';
require_once __DIR__ . '\Src\Exception\exception.php';


$db = new mysqli('127.0.0.1', 'root', '', 'bug');
exit;
$application = new \App\Helpers\App();
echo $application->getServerTime()->format('Y-m-d H:i:s') . PHP_EOL;
echo $application->getLogPath() . PHP_EOL;
echo $application->getEnvironment() . PHP_EOL;
echo $application->isDebugMode() . PHP_EOL;

if ($application->isRunningFromConsole()) {
    echo 'From Console';
} else {
    echo 'From Browser';
}
