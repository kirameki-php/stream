<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\FileWriter;
use function chmod;

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
        $file = 'tests/samples/permission.txt';
        $this->expectError();
        $this->expectErrorMessage("fopen({$file}): Failed to open stream: Operation not permitted");
        try {
            chmod($file, 0000);
            new FileWriter($file);
        } finally {
            chmod($file, 0644);
        }
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
        $stream = new FileWriter($file, true);
        $stream->write('b');
        $stream = new FileReader($file);
        self::assertNotSame('ab', $stream->read(5));
    }

    public function test_lock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        self::assertTrue($stream1->exclusiveLock());
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->exclusiveLock());
        self::assertTrue($stream2->unlock());
    }

    public function test_unlock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        self::assertTrue($stream->unlock());
        self::assertTrue($stream->exclusiveLock());
        self::assertTrue($stream->unlock());
    }

    public function test_withLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        $stream1->withExclusiveLock(function() use ($stream2) {
            self::assertFalse($stream2->exclusiveLock(false));
        });
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->unlock());
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
}
