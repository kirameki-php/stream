<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileStream;
use function file_put_contents;

class FileStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new FileStream('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        $stream->close();
    }

    public function test_read(): void
    {
        $path = '/tmp/close.txt';
        file_put_contents($path, 'abc');
        $stream = new FileStream($path);
        $stream->write('d');
        self::assertTrue($stream->seek(0));
        self::assertSame('abcd', $stream->read(5));
        self::assertSame(4, $stream->currentPosition());
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
