<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function error_get_last;
use function feof;
use function fread;
use function stream_get_line;
use const PHP_INT_MAX;

trait CanRead
{
    use CanLock;
    use ThrowsError;

    /**
     * @return resource
     */
    abstract public function getResource(): mixed;

    /**
     * @param int<0, max> $length
     * @return string
     */
    public function read(int $length): string
    {
        $data = @fread($this->getResource(), $length);
        if ($data === false) {
            $this->throwLastError([
                'length' => $length,
            ]);
        }
        return $data;
    }


    /**
     * @param int $length
     * @param string $ending
     * @return string|false
     */
    public function readLine(int $length = PHP_INT_MAX, string $ending = "\n"): string|false
    {
        $stream = $this->getResource();
        $line = @stream_get_line($stream, $length, $ending);
        if ($line === false && error_get_last()) {
            $this->throwLastError([
                'length' => $length,
                'ending' => $ending,
            ]);
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

    /**
     * @param StreamWritable $writer
     * @return int
     */
    public function copyTo(StreamWritable $writer): int
    {
        $size = 0;
        while (!$this->isEof()) {
            $size += $writer->write($this->read(4096));
        }
        return $size;
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return feof($this->getResource());
    }
}
