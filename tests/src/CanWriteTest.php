<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileStream;
use SouthPointe\Stream\FileWriter;
use SouthPointe\Stream\MemoryStream;
use TypeError;

class CanWriteTest extends TestCase
{
    public function test_write(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileStream($file);
        $stream->write('abc');
        $stream->rewind();
        self::assertSame('abc', $stream->read(5));
    }

    public function test_truncate_without_args(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->truncate();

        self::assertSame('', $stream->readToEnd());
    }

    public function test_truncate_with_size(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->truncate(1)->rewind();

        self::assertSame('a', $stream->readToEnd());
    }

    public function test_lock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        $stream1->exclusiveLock();
        $stream1->unlock();
        $stream2->exclusiveLock();
        $stream2->unlock();
        self::assertTrue(true);
    }

    public function test_unlock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        $stream->unlock();
        $stream->exclusiveLock();
        $stream->unlock();
        self::assertTrue(true);
    }

    public function test_withLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        $stream1->withExclusiveLock(function() use ($stream2) {
            $stream2->exclusiveLock(false);
        });
        $stream1->unlock();
        $stream2->unlock();
        self::assertTrue(true);
    }

    public function test_isOpen(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertTrue($stream->isOpen());
        self::assertTrue($stream->close());
        self::assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertFalse($stream->isClosed());
        self::assertTrue($stream->close());
        self::assertTrue($stream->isClosed());
    }

    public function test_close(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertTrue($stream->close());
    }

    public function test_write_after_close(): void
    {
        $path = 'tests/samples/write.txt';
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('fwrite(): supplied resource is not a valid stream resource');
        $stream = new FileWriter($path);
        $stream->close();
        $stream->write('def');
    }
}
