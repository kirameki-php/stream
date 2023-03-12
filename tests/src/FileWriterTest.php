<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use ErrorException;
use SouthPointe\Stream\Exceptions\ClosedException;
use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\FileWriter;
use TypeError;

class FileWriterTest extends TestCase
{
    public function test_construct(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        self::assertTrue($stream->isOpen());
    }

    public function test_with_permission(): void
    {
        $file = 'tests/samples/permission_denied.txt';
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("fopen({$file}): Failed to open stream: Permission denied");
        new FileWriter($file);
    }

    public function test_getFilePath(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        self::assertSame($file, $stream->getFilePath());
    }

    public function test_write(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        $stream->write('abc');
        $stream = new FileReader($file);
        self::assertSame('abc', $stream->read(5));
    }

    public function test_write_on_append(): void
    {
        $file = 'tests/samples/append.txt';
        $streamWrite = new FileWriter($file, true);
        $streamWrite->write('b');
        try {
            $streamRead = new FileReader($file);
            self::assertNotSame('ab', $streamRead->read(5));
        } finally {
            $streamWrite->truncate(7);
        }
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
    public function test_close_after_close(): void
    {
        $path = 'tests/samples/write.txt';
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('fclose(): supplied resource is not a valid stream resource');
        $stream = new FileWriter($path);
        $stream->close();
        $stream->close();
    }
}
