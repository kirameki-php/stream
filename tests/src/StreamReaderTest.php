<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\StreamReader;
use function chmod;

class StreamReaderTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
    }

    public function test_with_no_such_file(): void
    {
        $file = 'tests/samples/invalid.txt';
        $this->expectError();
        $this->expectErrorMessage("fopen({$file}): Failed to open stream: No such file or directory");
        new StreamReader($file);
    }

    public function test_with_permission(): void
    {
        $file = 'tests/samples/permission.txt';
        $this->expectError();
        $this->expectErrorMessage("fopen({$file}): Failed to open stream: Operation not permitted");
        try {
            chmod($file, 0000);
            new StreamReader($file);
        } finally {
            chmod($file, 0644);
        }
    }

    public function test_getFilePath(): void
    {
        $file = 'tests/samples/read.txt';
        $stream = new StreamReader($file);
        self::assertSame($file, $stream->getFilePath());
    }

    public function test_read(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->read(5));
        self::assertSame('', $stream->read(5));
    }

    public function test_read_with_empty(): void
    {
        $stream = new StreamReader('tests/samples/empty.txt');
        self::assertSame('', $stream->read(5));
    }

    public function test_lock(): void
    {
        $file = 'tests/samples/read.txt';
        $stream1 = new StreamReader($file);
        $stream2 = new StreamReader($file);
        self::assertTrue($stream1->lock());
        self::assertTrue($stream2->lock());
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->unlock());
    }

    public function test_unlock(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertTrue($stream->unlock());
        self::assertTrue($stream->lock());
        self::assertTrue($stream->unlock());
    }

    public function test_withLock(): void
    {
        $file = 'tests/samples/read.txt';
        $stream1 = new StreamReader($file);
        $stream2 = new StreamReader($file);
        $stream1->withLock(function() use ($stream2) {
            self::assertTrue($stream2->lock());
        });
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->unlock());
    }

    public function test_isOpen(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertTrue($stream->isOpen());
        self::assertTrue($stream->close());
        self::assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertFalse($stream->isClosed());
        self::assertTrue($stream->close());
        self::assertTrue($stream->isClosed());
    }

    public function test_isEol(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        $stream->read(5);
        self::assertTrue($stream->isEof());
    }

    public function test_close(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertTrue($stream->close());
    }
}
