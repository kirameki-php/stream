<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\StdinStream;

class StdinStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new StdinStream();
        $this->assertTrue($stream->isOpen());
        $this->assertSame('php://stdin', $stream->getUri());
        $this->assertSame('r', $stream->getMode());
        $stream->close();
    }
}
