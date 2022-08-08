<?php


namespace Tests\Units;


use App\Helpers\App;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testItCanGetInstanceOfApplication()
    {
        /**
         * Asserts that a variable is of a given type.
         *
         * @psalm-template ExpectedType of object
         * @psalm-param class-string<ExpectedType> $expected
         * @psalm-assert =ExpectedType $actual
         */
        self::assertInstanceOf(App::class, new App);
    }
}
