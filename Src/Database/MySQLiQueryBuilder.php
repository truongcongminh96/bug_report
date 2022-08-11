<?php


namespace App\Database;


use App\Exception\InvalidArgumentException;
use mysqli_result;
use ReflectionClass;
use ReflectionException;

class MySQLiQueryBuilder extends QueryBuilder
{
    /**
     * Since PHP 7.4 introduces type-hinting for properties, it is particularly important to provide valid values for all properties, so that all properties have values that match their declared types.
     * A property that has never been assigned doesn't have a null value, but it is on an undefined state, which will never match any declared type. undefined !== null.
     */
    private ?mysqli_result $resultSet = null;
    private array $results;

    const PARAM_TYPE_INT = 'i';
    const PARAM_TYPE_STRING = 's';
    const PARAM_TYPE_DOUBLE = 's';

    public function get(): array
    {
        $results = [];
        if (!$this->resultSet) {
            $this->resultSet = $this->statement->get_result();
            if ($this->resultSet) {
                while ($object = $this->resultSet->fetch_object()) {
                    $results[] = $object;
                }
                $this->results = $results;
            }
        }

        return $this->results;
    }

    public function count(): bool
    {
        if (!$this->resultSet) {
            $this->get();
        }

        return $this->resultSet ? $this->resultSet->num_rows : false;
    }

    public function lastInsertId()
    {
        return $this->connection->insert_id;
    }

    public function prepare($query)
    {
        return $this->connection->prepare($query);
    }

    public function execute($statement)
    {
        if (!$statement) {
            throw new InvalidArgumentException('MySQLi statement is false');
        }

        if ($this->bindings) {
            $bindings = $this->parseBindings($this->bindings);
            $reflectionObj = new ReflectionClass('mysqli_stmt');
            $method = $reflectionObj->getMethod('bind_param');
            try {
                $method->invokeArgs($statement, $bindings);
            } catch (ReflectionException $exception) {
                throw new ReflectionException(sprintf($exception->getMessage()));
            }
        }

        $statement->execute();
        $this->bindings = [];
        $this->placeholders = [];

        return $statement;
    }

    public function parseBindings(array $params): array
    {
        $bindings = [];
        $count = count($params);
        if ($count === 0) {
            return $this->bindings;
        }

        $bindingsTypes = $this->parseBindingTypes(); // "sids"
        $bindings[] = &$bindingsTypes;
        for ($index = 0; $index < $count; $index++) {
            $bindings[] = &$params[$index];
        }

        return $bindings;
    }

    public function parseBindingTypes(): string
    {
        $bindingTypes = [];
        foreach ($this->bindings as $binding) {
            if (is_int($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_INT;
            }
            if (is_string($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_STRING;
            }
            if (is_float($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_DOUBLE;
            }
        }

        return implode('', $bindingTypes);
    }

    public function fetchInto($className): array
    {
        $results = [];
        $this->resultSet = $this->statement->get_result();
        while ($object = $this->resultSet->fetch_object($className)) {
            $results[] = $object;
        }

        return $this->results = $results;
    }

    public function beginTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function affected(): int
    {
        $this->statement->store_result();
        return $this->statement->affected_rows;
    }
}
