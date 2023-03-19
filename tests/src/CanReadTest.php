<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\Exceptions\StreamErrorException;
use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\FileWriter;
use SouthPointe\Stream\MemoryStream;
use function file_get_contents;

class CanReadTest extends TestCase
{
    public function test_read(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->read(5));
        self::assertSame('', $stream->read(5));
    }

    public function test_read_with_empty(): void
    {
        $stream = new FileReader('tests/samples/empty.txt');
        self::assertSame('', $stream->read(5));
    }

    public function test_readLine(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        // specify length
        self::assertSame('1', $stream->readLine(1));
        // read to end
        self::assertSame('23', $stream->readLine());
        // over read
        self::assertFalse($stream->readLine());
    }

    public function test_readLine_fail_test(): void
    {
        $this->expectExceptionMessage('fread(): Read of 8192 bytes failed with errno=21 Is a directory');
        $this->expectException(StreamErrorException::class);
        $stream = new FileReader('tests/samples/');
        $stream->read(1);
    }

    public function test_readToEnd(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readToEnd());
    }

    public function test_readToEnd_with_buffer(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readToEnd(5));
    }

    public function test_copyTo(): void
    {
        $path = 'tests/samples/read.txt';
        $data = file_get_contents($path);
        $stream = new FileReader($path);
        $writer = new MemoryStream();
        $stream->copyTo($writer);
        self::assertSame($data, $writer->rewind()->readToEnd());
    }
}
