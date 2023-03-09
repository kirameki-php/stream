<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function feof;
use function stream_get_line;
use const PHP_INT_MAX;

class FileReader extends Stream implements StreamReadable, StreamSeekable
{
    use CanClose;
    use CanRead;
    use CanSeek;

    /**
     * @param string $path
     */
    public function __construct(
        string $path,
    )
    {
        parent::__construct($path, 'rb');
    }

    /**
     * @param int $length
     * @param string $ending
     * @return string|false
     */
    public function readLine(int $length = PHP_INT_MAX, string $ending = "\n"): string|false
    {
        $stream = $this->resource;
        $line = @stream_get_line($stream, $length, $ending);
        if ($line === false && $length > 1 && !feof($stream)) {
            $this->throwLastError();
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
