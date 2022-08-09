<?php


namespace Tests\Units;


use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{

    public function testItThrowMissingArgumentExceptionWithWrongCredentialKeys()
    {
        self::expectException(MissingArgumentException::class);
        $credentials = [];
        $pdoHandler = new PDOConnection($credentials);
    }

    public function testItCanConnectToDatabaseWithPdoApi(): PDOConnection
    {
        $credentials = $this->getCredentials('pdo');
        $pdoHandler = (new PDOConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $pdoHandler);
        return $pdoHandler;
    }

    /**
     * @depends testItCanConnectToDatabaseWithPdoApi
     * @param DatabaseConnectionInterface $handler
     */
    public function testItIsAValidPdoConnection(DatabaseConnectionInterface $handler)
    {
        self::assertInstanceOf(\PDO::class, $handler->getConnection());
    }

    private function getCredentials(string $type): array
    {
        return array_merge(
            Config::get('database', $type),
            ['db_name' => 'bug_app_testing']
        );
    }
}
