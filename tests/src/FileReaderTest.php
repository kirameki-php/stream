<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use ErrorException;
use SouthPointe\Stream\FileReader;

class FileReaderTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
    }

    public function test_with_no_such_file(): void
    {
        $file = 'tests/samples/invalid.txt';
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("fopen({$file}): Failed to open stream: No such file or directory");
        new FileReader($file);
    }

    public function test_getFilePath(): void
    {
        $file = 'tests/samples/read.txt';
        $stream = new FileReader($file);
        self::assertSame($file, $stream->getFilePath());
    }

    public function test_read(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->read(5));
        self::assertSame('', $stream->read(5));
    }

    public function test_read_with_empty(): void
    {
        $stream = new FileReader('tests/samples/empty.txt');
        self::assertSame('', $stream->read(5));
    }

    public function test_sharedLock(): void
    {
        $file = 'tests/samples/read.txt';
        $stream1 = new FileReader($file);
        $stream2 = new FileReader($file);
        $stream1->sharedLock();
        $stream2->sharedLock();
        $stream1->unlock();
        $stream2->unlock();
        self::assertTrue(true);
    }

    public function test_unlock(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $stream->unlock();
        $stream->sharedLock();
        $stream->unlock();
        self::assertTrue(true);
    }

    public function test_withLock(): void
    {
        $file = 'tests/samples/read.txt';
        $stream1 = new FileReader($file);
        $stream2 = new FileReader($file);
        $stream1->withSharedLock(function() use ($stream2) {
            $stream2->sharedLock();
        });
        $stream1->unlock();
        $stream2->unlock();
        self::assertTrue(true);
    }

    public function test_isOpen(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->isOpen());
        self::assertTrue($stream->close());
        self::assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertFalse($stream->isClosed());
        self::assertTrue($stream->close());
        self::assertTrue($stream->isClosed());
    }

    public function test_isEol(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        $stream->read(5);
        self::assertTrue($stream->isEof());
    }

    public function test_close(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertTrue($stream->close());
    }

    public function test_readLine(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        // specify length
        self::assertSame('1', $stream->readLine(1));
        // read to end
        self::assertSame('23', $stream->readLine());
        // over read
        self::assertFalse($stream->readLine());
    }

    public function test_readLine_fail_test(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('fread(): Read of 8192 bytes failed with errno=21 Is a directory');
        $stream = new FileReader('tests/samples/');
        $stream->readToEnd();
    }

    public function test_readToEnd(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readToEnd());
    }

    public function test_readToEnd_with_buffer(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readToEnd(5));
    }
}
