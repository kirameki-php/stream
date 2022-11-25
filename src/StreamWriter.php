<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use RuntimeException;
use function error_get_last;
use function flock;
use function fwrite;
use function json_encode;
use const JSON_THROW_ON_ERROR;
use const LOCK_EX;
use const LOCK_NB;

class StreamWriter extends Stream
{
    /**
     * @param string $file
     * @param bool $append
     */
    public function __construct(
        string $file,
        bool $append = false,
    )
    {
        parent::__construct($file, $append ? 'a' : 'w');
    }

    /**
     * @param string $data
     * @param int<0, max>|null $length
     * @return int
     */
    public function write(string $data, ?int $length = null): int
    {
        $bytesWritten = fwrite($this->stream, $data, $length);
        if ($bytesWritten === false) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
        return $bytesWritten;
    }

    /**
     * @param bool $blocking
     * @return bool
     */
    public function lock(bool $blocking = true): bool
    {
        return flock(
            $this->stream,
            $blocking ? LOCK_EX : LOCK_EX | LOCK_NB
        );
    }
}
