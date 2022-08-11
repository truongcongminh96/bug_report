<?php

declare(strict_types=1);

namespace App\Database;


use App\Exception\InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

trait Query
{
    #[Pure] public function getQuery(string $type)
    {
        return match ($type) {
            self::DML_TYPE_SELECT => sprintf(
                "SELECT %s FROM %s WHERE %s",
                $this->fields, $this->table, implode(' and ', $this->placeholders)
            ),
            self::DML_TYPE_INSERT => sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $this->table, $this->fields, implode(',', $this->placeholders)
            ),
            self::DML_TYPE_UPDATE => sprintf(
                "UPDATE %s SET %s WHERE %s",
                $this->table, implode(', ', $this->fields), implode(' and ', $this->placeholders)
            ),
            self::DML_TYPE_DELETE => sprintf(
                "DELETE FROM %s WHERE %s",
                $this->table, implode(' and ', $this->placeholders)
            ),
            default => throw new InvalidArgumentException('Dml type not supported'),
        };
    }
}
