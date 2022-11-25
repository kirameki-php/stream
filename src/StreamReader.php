<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use RuntimeException;
use function error_get_last;
use function feof;
use function flock;
use function fread;
use function json_encode;
use const JSON_THROW_ON_ERROR;
use const LOCK_NB;
use const LOCK_SH;

class StreamReader extends Stream
{
    /**
     * @param string $file
     */
    public function __construct(
        string $file,
    )
    {
        parent::__construct($file, 'r');
    }

    /**
     * @param int<0, max> $length
     * @return string
     */
    public function read(int $length): string
    {
        $data = fread($this->stream, $length);
        if ($data === false) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
        return $data;
    }

    /**
     * @param bool $blocking
     * @return bool
     */
    public function lock(bool $blocking = true): bool
    {
        return flock(
            $this->stream,
            $blocking ? LOCK_SH : LOCK_SH | LOCK_NB
        );
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return feof($this->stream);
    }
}
