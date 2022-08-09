<?php


namespace Tests\Units;


use App\Contracts\LoggerInterface;
use App\Exception\InvalidLogLevelArgument;
use App\Helpers\App;
use App\Logger\Logger;
use App\Logger\LogLevel;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    /** @var Logger $logger */
    private Logger $logger;

    public function setUp(): void
    {
        $this->logger = new Logger;
        parent::setUp();
    }

    public function testItImplementsTheLoggerInterface()
    {
        self::assertInstanceOf(LoggerInterface::class, new Logger);
    }

    public function testItCanCreateDifferentTypesOfLogLevel()
    {
        $this->logger->info('Testing Info logs');
        $this->logger->error('Testing Error logs');
        $this->logger->log(LogLevel::ALERT, 'Testing Alert logs');
        $app = new App;

        $fileName = sprintf("%s/%s-%s.log", $app->getLogPath(), 'test', date("j.n.Y"));
        self::assertFileExists($fileName);

        $contentOfLogFile = file_get_contents($fileName);
        self::assertStringContainsString('Testing Info logs', $contentOfLogFile);
        self::assertStringContainsString('Testing Error logs', $contentOfLogFile);
        self::assertStringContainsString(LogLevel::ALERT, $contentOfLogFile);
        unlink($fileName);

        /**
         * Replace assertFileNotExists() method
         * https://github.com/sebastianbergmann/phpunit/issues/4077
         */
        self::assertFileDoesNotExist($fileName);
    }

    public function testItThrowsInvalidLogLevelArgumentExceptionWhenGivenAWrongLogLevel()
    {
        self::expectException(InvalidLogLevelArgument::class);
        $this->logger->log('invalid', 'Testing invalid log level');
    }
}
