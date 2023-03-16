<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileStream;
use function file_put_contents;

class FileStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $path = 'tests/samples/read.txt';
        $stream = new FileStream($path);
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertSame($path, $stream->getFilePath());
        self::assertSame('r+b', $stream->getMode());
        $stream->close();
    }

    public function test_write_without_append(): void
    {
        $path = '/tmp/close.txt';
        file_put_contents($path, 'abc');
        $stream = new FileStream($path);
        $stream->write('def');
        self::assertTrue($stream->seek(0));
        self::assertSame('def', $stream->read(5));
        self::assertSame(3, $stream->currentPosition());
        $stream->close();
    }

    public function test_write_with_append(): void
    {
        $path = '/tmp/close.txt';
        file_put_contents($path, 'abc');
        $stream = new FileStream($path, 'ab+');
        $stream->write('def');
        self::assertTrue($stream->seek(0));
        self::assertSame('abcdef', $stream->read(6));
        self::assertSame(6, $stream->currentPosition());
        $stream->close();
    }

    public function test_close(): void
    {
        $path = '/tmp/close.txt';
        file_put_contents($path, 'abc');
        $stream = new FileStream($path);
        $stream->close();
        self::assertTrue($stream->isClosed());
        self::assertFalse($stream->isOpen());
    }
}
