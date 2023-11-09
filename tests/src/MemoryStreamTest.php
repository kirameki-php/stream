<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\MemoryStream;

class MemoryStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new MemoryStream();
        $this->assertFalse($stream->isEof());
        $this->assertTrue($stream->isOpen());
        $this->assertSame('php://memory', $stream->getUri());
        $this->assertSame('w+b', $stream->getMode());
        $stream->close();
    }
}
