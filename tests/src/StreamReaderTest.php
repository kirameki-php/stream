<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\StreamReader;

class StreamReaderTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
    }

    public function test_with_invalid(): void
    {
        $file = 'tests/samples/invalid.txt';
        $this->expectError();
        $this->expectErrorMessage("fopen({$file}): Failed to open stream: No such file or directory");
        new StreamReader($file);
    }

    public function test_getFile(): void
    {
        $file = 'tests/samples/read.txt';
        $stream = new StreamReader($file);
        self::assertEquals($file, $stream->getFile());
    }

    public function test_read(): void
    {
        $stream = new StreamReader('tests/samples/read.txt');
        self::assertEquals("123\n", $stream->read(5));
        self::assertEquals('', $stream->read(5));
    }

    public function test_read_with_empty(): void
    {
        $stream = new StreamReader('tests/samples/empty.txt');
        self::assertEquals('', $stream->read(5));
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
