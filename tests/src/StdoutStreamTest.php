<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\StdoutStream;

class StdoutStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new StdoutStream();
        $this->assertTrue($stream->isOpen());
        $this->assertSame('php://stdout', $stream->getUri());
        $this->assertSame('w', $stream->getMode());
        $stream->close();
    }
}
