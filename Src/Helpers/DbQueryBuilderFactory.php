<?php


namespace App\Helpers;


use App\Database\MySQLiConnection;
use App\Database\MySQLiQueryBuilder;
use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Database\QueryBuilder;
use App\Exception\DatabaseConnectionException;
use App\Exception\NotFoundException;

class DbQueryBuilderFactory
{
    public static function make(
        string $credentialsFile = 'database',
        string $connectionType = 'pdo',
        array $options = []
    ): QueryBuilder
    {
        $connection = null;
        try {
            $credentials = array_merge(Config::get($credentialsFile, $connectionType), $options);
        } catch (NotFoundException $exception) {
            throw new NotFoundException(sprintf($exception->getMessage()));
        }

        switch ($connectionType) {
            case 'pdo':
                $connection = (new PDOConnection($credentials))->connect();
                return new PDOQueryBuilder($connection);
                break;
            case 'mysqli':
                $connection = (new MySQLiConnection($credentials))->connect();
                return new MySQLiQueryBuilder($connection);
                break;
            default:
                throw new DatabaseConnectionException(
                    "Connection type is not recognize internally",
                    ['type' => $connectionType]
                );
        }
    }
}
