<?php declare(strict_types=1);

namespace SouthPointe\Core;

use JsonSerializable;
use LogicException as BaseException;
use Throwable;

class LogicException extends BaseException implements ContextualThrowable, JsonSerializable
{
    use WithContext;
    use WithJsonSerialize;

    /**
     * @param string $message
     * @param iterable<int|string, mixed>|null $context
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        ?iterable $context = null,
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->setContext($context);
    }
}
