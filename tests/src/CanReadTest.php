<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\Exceptions\StreamErrorException;
use Kirameki\Stream\FileReader;
use Kirameki\Stream\FileWriter;
use Kirameki\Stream\MemoryStream;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use function dump;
use function error_clear_last;
use function file_get_contents;

class CanReadTest extends TestCase
{
    public function test_read(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertSame("123\n", $stream->read(5));
        $this->assertSame('', $stream->read(5));
    }

    public function test_read_with_empty(): void
    {
        $stream = new FileReader('tests/samples/empty.txt');
        $this->assertSame('', $stream->read(5));
    }

    #[WithoutErrorHandler]
    public function test_read_fail_test(): void
    {
        $this->expectExceptionMessage('fread(): Read of 8192 bytes failed with errno=21 Is a directory');
        $this->expectException(StreamErrorException::class);
        $stream = new FileReader('tests/samples/');
        $stream->read(1);
    }

    public function test_readLine(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        // specify length
        $this->assertSame('1', $stream->readLine(1));
        // read to end
        $this->assertSame('23', $stream->readLine());
        // over read
        $this->assertSame('', $stream->readLine());
    }

    #[WithoutErrorHandler]
    public function test_readLine_fail_test(): void
    {
        $this->expectExceptionMessage('stream_get_line(): Read of 8192 bytes failed with errno=21 Is a directory');
        $this->expectException(StreamErrorException::class);
        $stream = new FileReader('tests/samples/');
        $stream->readLine();
    }

    public function test_readToEnd(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertSame("123\n", $stream->readToEnd());
        $stream->seek(1);
        $this->assertSame("23\n", $stream->readToEnd());
    }

    public function test_readToEnd_with_buffer(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertSame("123\n", $stream->readToEnd(1));
    }
}
