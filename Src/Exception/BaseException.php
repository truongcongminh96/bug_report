<?php


namespace App\Exception;


use JetBrains\PhpStorm\Pure;
use Throwable;

abstract class BaseException extends \Exception
{
    protected array $data = [];

    /**
     * The attribute marks the function that has no impact on the program state or passed parameters used after the function execution.
     * This means that a function call that resolves to such a function can be safely removed if the execution result is not used in code afterwards.
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @param Throwable|null $previous
     * @since 8.0
     */
    #[Pure] public function __construct(
        $message = "",
        array $data = [],
        $code = 0,
        Throwable $previous = null
    )
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function setData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function getExtraData(): array
    {
        if (count($this->data) === 0) {
            return $this->data;
        }
        return json_decode(json_encode($this->data), true);
    }
}
