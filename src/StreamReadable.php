<?php declare(strict_types=1);

namespace SouthPointe\Stream;

interface StreamReadable
{
    /**
     * @param int<0, max> $length
     * @return string
     */
    function read(int $length): string;

    /**
     * @param StreamWritable $writer
     * @return int
     */
    function copyTo(StreamWritable $writer): int;

    /**
     * @return bool
     */
    function isEof(): bool;

    /**
     * @param bool $blocking
     * @return bool
     */
    function sharedLock(bool $blocking = true): bool;

    /**
     * @return bool
     */
    function unlock(): bool;
}
