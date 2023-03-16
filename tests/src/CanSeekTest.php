<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\MemoryStream;

class CanSeekTest extends TestCase
{
    public function test_seek(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->seek(1));
        self::assertSame("23\n", $stream->readToEnd());
    }

    public function test_seek_negative(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertFalse($stream->seek(-1));
        self::assertSame("1", $stream->read(1));
    }

    public function test_seek_out_of_bound(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->seek(10));
        self::assertSame("", $stream->readToEnd());
    }

    public function test_seek_at_end(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->seek(4));
        self::assertSame("", $stream->readToEnd());
    }

    public function test_currentPosition(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->seek(3));
        self::assertSame(3, $stream->currentPosition());
    }

    public function test_currentPosition_overrun(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->seek(10));
        self::assertSame(10, $stream->currentPosition());
    }

    public function test_currentPosition_underrun(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertFalse($stream->seek(-1));
        self::assertSame(0, $stream->currentPosition());
    }

    public function test_rewind(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->write('def');
        $stream->rewind();
        self::assertSame('abcdef', $stream->readToEnd());
        $stream->close();
    }

    public function test_fastForward_from_start(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->rewind();
        $stream->fastForward();
        self::assertSame(3, $stream->currentPosition());
        $stream->close();
    }

    public function test_fastForward_from_end(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->fastForward();
        self::assertSame(3, $stream->currentPosition());
        $stream->close();
    }
}
