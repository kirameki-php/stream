<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use RuntimeException;
use function error_get_last;
use function feof;
use function fgets;
use function flock;
use function fread;
use function json_encode;
use const JSON_THROW_ON_ERROR;
use const LOCK_NB;
use const LOCK_SH;

class FileReader extends StreamReader
{
    /**
     * @param int<0, max>|null $length
     * @return string
     */
    public function readLine(?int $length = null): string|false
    {
        $stream = $this->stream;
        $line = fgets($stream, $length);
        if ($line === false && !feof($stream)) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
        return $line;
    }

    /**
     * @param int<0, max> $buffer
     * @return string
     */
    public function readToEnd(int $buffer = 1_000): string
    {
        $string = '';
        while(true) {
            $line = $this->read($buffer);
            if ($line === '') {
                break;
            }
            $string .= $line;
        }
        return $string;
    }
}
