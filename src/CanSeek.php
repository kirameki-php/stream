<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use RuntimeException;
use function error_get_last;
use function fseek;
use function ftell;
use function json_encode;
use function rewind;
use const JSON_THROW_ON_ERROR;
use const SEEK_SET;

trait CanSeek
{
    /**
     * @return resource
     */
    abstract protected function getStream(): mixed;

    /**
     * @return int
     */
    function currentPosition(): int
    {
        $position = ftell($this->getStream());
        if ($position === false) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
        return $position;
    }

    /**
     * @return void
     */
    function rewind(): void
    {
        $result = rewind($this->getStream());
        if ($result === false) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return void
     */
    function seek(int $offset, int $whence = SEEK_SET): void
    {
        $result = fseek($this->getStream(), $offset, $whence);
        if ($result === -1) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
    }
}
