<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use Kirameki\Core\Exceptions\RuntimeException;
use SouthPointe\Stream\RandomFileStream;
use function unlink;

class RandomFileStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new RandomFileStream();
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/tmp/kirameki', $stream->getUri());
        self::assertSame('r+b', $stream->getMode());
        $stream->close();
    }

    public function test_construct_with_prefix(): void
    {
        $stream = new RandomFileStream('test');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/tmp/test', $stream->getUri());
        $stream->close();
        self::assertFileExists($stream->getUri());
    }

    public function test_construct_with_dir(): void
    {
        $stream = new RandomFileStream('test', '/var/tmp');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/var/tmp/test', $stream->getUri());
        $stream->close();
        self::assertFileExists($stream->getUri());
        unlink($stream->getUri());
    }

    public function test_construct_with_persist(): void
    {
        $stream = new RandomFileStream(__FUNCTION__, '/var/tmp', false);
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertStringStartsWith('/var/tmp/' . __FUNCTION__, $stream->getUri());
        self::assertFileExists($stream->getUri());
        $stream->close();
        self::assertFileDoesNotExist($stream->getUri());
    }

    public function test_close(): void
    {
        $stream = new RandomFileStream();
        $stream->close();
        self::assertTrue($stream->isClosed());
        self::assertFalse($stream->isOpen());
    }

    public function test_unwritable(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not create file at /missing');
        new RandomFileStream(dir: '/missing');
    }
}
