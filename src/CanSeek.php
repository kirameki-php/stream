<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fseek;
use function ftell;
use function rewind;
use const SEEK_END;
use const SEEK_SET;

trait CanSeek
{
    use ThrowsError;

    /**
     * @return resource
     */
    abstract public function getResource(): mixed;

    /**
     * @return int
     */
    function currentPosition(): int
    {
        $position = @ftell($this->getResource());
        if ($position === false) {
            $this->throwLastError();
        }
        return $position;
    }

    /**
     * @return static
     */
    function rewind(): static
    {
        $result = @rewind($this->getResource());
        if ($result === false) {
            $this->throwLastError();
        }
        return $this;
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return static
     */
    function seek(int $offset, int $whence = SEEK_SET): static
    {
        $result = @fseek($this->getResource(), $offset, $whence);
        if ($result === -1) {
            $this->throwLastError();
        }
        return $this;
    }

    /**
     * @return $this
     */
    function seekToEnd(): static
    {
        return $this->seek(0, SEEK_END);
    }
}
