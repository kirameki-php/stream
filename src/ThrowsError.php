<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Kirameki\Core\Exceptions\UnreachableException;
use SouthPointe\Stream\Exceptions\StreamErrorException;
use function error_clear_last;
use function error_get_last;
use const E_ERROR;

trait ThrowsError
{
    /**
     * @param iterable<string, mixed>|null $context
     * @return never
     */
    protected function throwLastError(
        ?iterable $context = null,
    ): never
    {
        $error = error_get_last() ?? throw new UnreachableException();
        error_clear_last();

        $context ??= [];
        $context += [
            'stream' => $this,
        ];

        throw new StreamErrorException(
            $error['message'] ?? '',
            $error['type'] ?? E_ERROR,
            $error['file'] ?? '',
            $error['line'] ?? 0,
            $context,
        );
    }
}
