<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\Exceptions\StreamErrorException;
use Kirameki\Stream\FileReader;
use Kirameki\Stream\MemoryStream;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use function file_get_contents;

class CanScanTest extends TestCase
{
    public function test_readFromStartToEnd(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readFromStartToEnd());
        $stream->seek(1);
        self::assertSame("123\n", $stream->readFromStartToEnd());
    }

    public function test_copyTo(): void
    {
        $path = 'tests/samples/read.txt';
        $data = file_get_contents($path);
        $stream = new FileReader($path);
        $writer = new MemoryStream();
        $stream->copyTo($writer);
        self::assertSame($data, $writer->readFromStartToEnd());
    }

    public function test_copyTo_no_rewind(): void
    {
        $path = 'tests/samples/read.txt';
        $stream = new FileReader($path);
        $stream->seek(1);
        $writer = new MemoryStream();
        $stream->copyTo($writer, rewind: false);
        self::assertSame("23\n", $writer->readFromStartToEnd());
    }

    public function test_copyTo_with_buffer(): void
    {
        $path = 'tests/samples/read.txt';
        $data = file_get_contents($path);
        $stream = new FileReader($path);
        $writer = new MemoryStream();
        $stream->copyTo($writer, 1);
        self::assertSame($data, $writer->readFromStartToEnd());
    }
}
