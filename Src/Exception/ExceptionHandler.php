<?php
declare(strict_types=1);

namespace App\Exception;


use App\Helpers\App;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

class ExceptionHandler
{
    #[NoReturn] public function handler(Throwable $exception): void
    {
        $application = new App;

        if ($application->isDebugMode()) {
            var_dump($exception);
        } else {
            echo 'This should not have happened, please try again';
        }

        exit;
    }

    public function convertWarningsAndNoticesException($severity, $message, $file, $line)
    {
        throw new \ErrorException($severity, $message, $file, $line);
    }
}
