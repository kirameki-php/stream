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
     * @return never
     */
    protected function throwLastError(): never
    {
        $error = error_get_last() ?? throw new UnreachableException();
        error_clear_last();
        throw new StreamErrorException(
            $error['message'] ?? '',
            $error['type'] ?? E_ERROR,
            $error['file'] ?? '',
            $error['line'] ?? 0,
        );
    }
}
