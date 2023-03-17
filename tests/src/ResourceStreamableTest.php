<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\MemoryStream;
use TypeError;

class ResourceStreamableTest extends TestCase
{
    public function test_getResource(): void
    {
        $stream = new MemoryStream();
        self::assertIsResource($stream->getResource());
    }

    public function test_getMetaData(): void
    {
        $stream = new MemoryStream();
        $meta = $stream->getMetadata();
        self::assertSame([
            'timed_out' => false,
            'blocked' => true,
            'eof' => false,
            'wrapper_type' => 'PHP',
            'stream_type' => 'MEMORY',
            'mode' => 'w+b',
            'unread_bytes' => 0,
            'seekable' => true,
            'uri' => 'php://memory',
        ], $meta);
    }

    public function test_getFilePath(): void
    {
        $stream = new MemoryStream();
        self::assertSame('php://memory', $stream->getUri());
        // can return info even after closed.
        $stream->close();
        self::assertSame('php://memory', $stream->getUri());
    }

    public function test_getMode(): void
    {
        $stream = new MemoryStream();
        self::assertSame('w+b', $stream->getMode());
        // can return info even after closed.
        $stream->close();
        self::assertSame('w+b', $stream->getMode());
    }

    public function test_isOpen(): void
    {
        $stream = new MemoryStream();
        self::assertTrue($stream->isOpen());
        $stream->close();
        self::assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new MemoryStream();
        self::assertFalse($stream->isClosed());
        $stream->close();
        self::assertTrue($stream->isClosed());
    }

    public function test_isEof(): void
    {
        $stream = new MemoryStream();
        self::assertFalse($stream->isEof());
        // reading once allows eof flag to be set.
        self::assertSame('', $stream->read(1));
        self::assertTrue($stream->isEof());
    }

    public function test_close(): void
    {
        $stream = new MemoryStream();
        $stream->close();
        self::assertTrue($stream->isClosed());
        self::assertFalse($stream->isOpen());
    }

    public function test_close_after_close(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('fclose(): supplied resource is not a valid stream resource');
        $stream = new MemoryStream();
        $stream->close();
        $stream->close();
    }
}
