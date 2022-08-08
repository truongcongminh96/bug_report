<?php
declare(strict_types=1);

require_once __DIR__ . '\vendor\autoload.php';

set_exception_handler([new App\Exception\ExceptionHandler(), 'handler']);
$config = \App\Helpers\Config::getFileContent('notfoundfile');
var_dump($config);

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
