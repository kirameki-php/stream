<?php declare(strict_types=1);

namespace SouthPointe\Stream;

interface StreamSeekable extends Streamable
{
    /**
     * @return int
     */
    function currentPosition(): int;

    /**
     * @return void
     */
    function rewind(): void;

    /**
     * @param int $offset
     * @param int $whence
     * @return void
     */
    function seek(int $offset, int $whence = SEEK_SET): void;
}
