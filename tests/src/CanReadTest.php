<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use ErrorException;
use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\FileWriter;
use SouthPointe\Stream\MemoryStream;
use function file_get_contents;

class CanReadTest extends TestCase
{
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

    public function test_withExAndShLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileReader($file);
        $stream1->withExclusiveLock(function() use ($stream2) {
            self::assertFalse($stream2->sharedLock(false));
        });
        $stream1->unlock();
        $stream2->unlock();
        self::assertTrue(true);
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
        $stream->read(1);
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

    public function test_copyTo(): void
    {
        $path = 'tests/samples/read.txt';
        $data = file_get_contents($path);
        $stream = new FileReader($path);
        $writer = new MemoryStream();
        $stream->copyTo($writer);
        self::assertSame($data, $writer->rewind()->readToEnd());
    }
}
