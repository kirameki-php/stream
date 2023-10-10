<?php declare(strict_types=1);

namespace Kirameki\Stream;

interface StreamReadable
{
    /**
     * @param int<0, max> $length
     * @return string
     */
    function read(int $length): string;

    /**
     * @return bool
     */
    function isEof(): bool;
}
