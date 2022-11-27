<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use ErrorException;
use function error_get_last;
use const E_ERROR;

trait ThrowsError
{
    /**
     * @return never
     */
    protected function throwLastError(): never
    {
        $error = error_get_last() ?? [];
        throw new StreamException(
            $error['message'] ?? '',
            0,
            $error['type'] ?? E_ERROR,
            $error['file'] ?? '',
            $error['line'] ?? 0,
        );
    }
}
