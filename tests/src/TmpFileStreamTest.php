<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\TmpFileStream;

class TmpFileStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new TmpFileStream();
        $this->assertFalse($stream->isEof());
        $this->assertTrue($stream->isOpen());
        $this->assertStringStartsWith('/tmp/php', $stream->getUri());
        $this->assertSame('r+b', $stream->getMode());
        $this->assertFileExists($stream->getUri());
        // Closing will remove tmpfile
        $stream->close();
        $this->assertFileDoesNotExist($stream->getUri());
    }
}
