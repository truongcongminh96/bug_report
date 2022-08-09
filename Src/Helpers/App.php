<?php

declare(strict_types=1);

namespace App\Helpers;


use JetBrains\PhpStorm\Pure;
use DateTimeInterface, DateTime, DateTimeZone;

class App
{
    private array $config = [];

    public function __construct()
    {
        $this->config = Config::get('app');
    }

    public function isDebugMode(): bool
    {
        if (!isset($this->config['debug'])) {
            return false;
        }

        return $this->config['debug'];
    }

    public function getEnvironment(): string
    {
        if (!isset($this->config['env'])) {
            return 'production';
        }

        return $this->isTestMode() ? 'test' : $this->config['env'];
    }

    public function getLogPath(): string
    {
        if (!isset($this->config['log_path'])) {
            throw new \Exception('Log path is not defined');
        }

        return $this->config['log_path'];
    }

    #[Pure] public function isRunningFromConsole(): bool
    {
        /**
         * Returns the type of interface between web server and PHP
         * @link https://php.net/manual/en/function.php-sapi-name.php
         * @return string the interface type, as a lowercase string.
         * </p>
         * <p>
         * Although not exhaustive, the possible return values include
         * aolserver, apache,
         * apache2filter, apache2handler,
         * caudium, cgi (until PHP 5.3),
         * cgi-fcgi, cli,
         * continuity, embed,
         * isapi, litespeed,
         * milter, nsapi,
         * phttpd, pi3web, roxen,
         * thttpd, tux, and webjames.
         */
        return php_sapi_name() == 'cli' || php_sapi_name() == 'phpdbg';
    }

    public function getServerTime(): DateTimeInterface
    {
        try {
            return new DateTime('now', new DateTimeZone('ASIA/Ho_Chi_Minh'));
        } catch (\Throwable $exception) {
            throw new \RuntimeException(
                sprintf('Error: %s', $exception->getMessage())
            );
        }
    }

    #[Pure] public function isTestMode(): bool
    {
        if ($this->isRunningFromConsole() && defined('PHPUNIT_RUNNING') && PHPUNIT_RUNNING == true) {
            return true;
        }

        return false;
    }
}
