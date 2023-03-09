<?php declare(strict_types=1);

namespace SouthPointe\Stream;

interface StreamSeekable extends Streamable
{
    /**
     * @return int
     */
    function currentPosition(): int;

    /**
     * @return static
     */
    function rewind(): static;

    /**
     * @param int $offset
     * @param int $whence
     * @return static
     */
    function seek(int $offset, int $whence = SEEK_SET): static;
}
