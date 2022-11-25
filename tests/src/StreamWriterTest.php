<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\StreamReader;
use SouthPointe\Stream\StreamWriter;
use function chmod;

class StreamWriterTest extends TestCase
{
    public function test_construct(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new StreamWriter($file);
        self::assertTrue($stream->isOpen());
    }

    public function test_with_permission(): void
    {
        $file = 'tests/samples/permission.txt';
        $this->expectError();
        $this->expectErrorMessage("fopen({$file}): Failed to open stream: Operation not permitted");
        try {
            chmod($file, 0000);
            new StreamWriter($file);
        } finally {
            chmod($file, 0644);
        }
    }

    public function test_getFilePath(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new StreamWriter($file);
        self::assertSame($file, $stream->getFilePath());
    }

    public function test_write(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new StreamWriter($file);
        $stream->write('abc');
        $stream = new StreamReader($file);
        self::assertSame('abc', $stream->read(5));
    }

    public function test_lock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new StreamWriter($file);
        $stream2 = new StreamWriter($file);
        self::assertTrue($stream1->lock());
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->lock());
        self::assertTrue($stream2->unlock());
    }

    public function test_unlock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new StreamWriter($file);
        self::assertTrue($stream->unlock());
        self::assertTrue($stream->lock());
        self::assertTrue($stream->unlock());
    }

    public function test_withLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new StreamWriter($file);
        $stream2 = new StreamWriter($file);
        $stream1->withLock(function() use ($stream2) {
            self::assertFalse($stream2->lock(false));
        });
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->unlock());
    }

    public function test_isOpen(): void
    {
        $stream = new StreamWriter('tests/samples/write.txt');
        self::assertTrue($stream->isOpen());
        self::assertTrue($stream->close());
        self::assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new StreamWriter('tests/samples/write.txt');
        self::assertFalse($stream->isClosed());
        self::assertTrue($stream->close());
        self::assertTrue($stream->isClosed());
    }

    public function test_close(): void
    {
        $stream = new StreamWriter('tests/samples/write.txt');
        self::assertTrue($stream->close());
    }
}
