<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use RuntimeException;
use function error_get_last;
use function feof;
use function json_encode;
use function stream_get_line;
use const JSON_THROW_ON_ERROR;
use const PHP_INT_MAX;

class FileReader extends Stream
{
    use CanRead;
    use CanSeek;

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
     * @param int $length
     * @param string $ending
     * @return string|false
     */
    public function readLine(int $length = PHP_INT_MAX, string $ending = "\n"): string|false
    {
        $stream = $this->stream;
        $line = stream_get_line($stream, $length, $ending);
        if ($line === false && $length > 1 && !feof($stream)) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
        return $line;
    }

    /**
     * @param int<0, max> $buffer
     * @return string
     */
    public function readToEnd(int $buffer = 4096): string
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
