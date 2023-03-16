<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use Kirameki\Core\Exceptions\Exceptionable;
use Kirameki\Core\Exceptions\RuntimeException;
use SouthPointe\Stream\TmpFileStream;
use function dump;
use function tempnam;
use function unlink;

class TmpFileStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new TmpFileStream();
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/tmp/kirameki', $stream->getFilePath());
        self::assertSame('rb+', $stream->getMode());
        $stream->close();
    }

    public function test_construct_with_prefix(): void
    {
        $stream = new TmpFileStream('test');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/tmp/test', $stream->getFilePath());
        $stream->close();
        self::assertFileExists($stream->getFilePath());
    }

    public function test_construct_with_dir(): void
    {
        $stream = new TmpFileStream('test', '/var/tmp');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/var/tmp/test', $stream->getFilePath());
        $stream->close();
        self::assertFileExists($stream->getFilePath());
        unlink($stream->getFilePath());
    }

    public function test_construct_with_persist(): void
    {
        $stream = new TmpFileStream(__FUNCTION__, '/var/tmp', false);
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/var/tmp/' . __FUNCTION__, $stream->getFilePath());
        self::assertFileExists($stream->getFilePath());
        $stream->close();
        self::assertFileDoesNotExist($stream->getFilePath());
    }

    public function test_close(): void
    {
        $stream = new TmpFileStream();
        $stream->close();
        self::assertTrue($stream->isClosed());
        self::assertFalse($stream->isOpen());
    }

    public function test_unwritable(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not create file at /missing');
        new TmpFileStream(dir: '/missing');
    }
}
