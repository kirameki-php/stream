<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Core\Exceptions\UnreachableException;
use Kirameki\Stream\CanSeek;
use Kirameki\Stream\Exceptions\StreamErrorException;
use Kirameki\Stream\FileReader;
use Kirameki\Stream\MemoryStream;
use Kirameki\Stream\StdoutStream;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use TypeError;

class CanSeekTest extends TestCase
{
    public function test_seek(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertTrue($stream->seek(1));
        $this->assertSame("23\n", $stream->readToEnd());
    }

    public function test_seek_negative(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertFalse($stream->seek(-1));
        $this->assertSame("1", $stream->read(1));
    }

    public function test_seek_out_of_bound(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertTrue($stream->seek(10));
        $this->assertSame("", $stream->readToEnd());
    }

    public function test_seek_at_end(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertTrue($stream->seek(4));
        $this->assertSame("", $stream->readToEnd());
    }

    public function test_currentPosition(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertTrue($stream->seek(3));
        $this->assertSame(3, $stream->currentPosition());
    }

    public function test_currentPositionImmediate(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertSame(0, $stream->currentPosition());
    }

    public function test_currentPosition_overrun(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertTrue($stream->seek(10));
        $this->assertSame(10, $stream->currentPosition());
    }

    public function test_currentPosition_underrun(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertFalse($stream->seek(-1));
        $this->assertSame(0, $stream->currentPosition());
    }

    public function test_currentPosition_on_non_seekable(): void
    {
        $this->expectException(UnreachableException::class);
        // TODO fix php-src
        $this->expectExceptionMessage('');
        $stream = new class() extends StdoutStream { use CanSeek; };
        $stream->currentPosition();
    }

    public function test_rewind(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->write('def');
        $stream->rewind();
        $this->assertSame('abcdef', $stream->readToEnd());
        $stream->close();
    }

    #[WithoutErrorHandler]
    public function test_rewind_on_non_seekable(): void
    {
        $this->expectExceptionMessage('rewind(): Stream does not support seeking');
        $this->expectException(StreamErrorException::class);
        $stream = new class() extends StdoutStream { use CanSeek; };
        $stream->rewind();
    }

    public function test_rewind_after_close(): void
    {
        $this->expectExceptionMessage('rewind(): supplied resource is not a valid stream resource');
        $this->expectException(TypeError::class);
        $stream = new MemoryStream();
        $stream->close();
        $stream->rewind();
    }

    public function test_fastForward_from_start(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->rewind();
        $stream->fastForward();
        $this->assertSame(3, $stream->currentPosition());
        $stream->close();
    }

    public function test_fastForward_from_end(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->fastForward();
        $this->assertSame(3, $stream->currentPosition());
        $stream->close();
    }
}
