<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\MemoryStream;

class MemoryStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new MemoryStream();
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertSame('php://memory', $stream->getUri());
        self::assertSame('w+b', $stream->getMode());
        $stream->close();
    }

    public function test_close(): void
    {
        $stream = new MemoryStream();
        $stream->close();
        self::assertTrue($stream->isClosed());
        self::assertFalse($stream->isOpen());
    }
}
