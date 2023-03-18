<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\StderrStream;

class StderrStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new StderrStream();
        self::assertTrue($stream->isOpen());
        self::assertSame('php://stderr', $stream->getUri());
        self::assertSame('w', $stream->getMode());
        $stream->close();
    }
}
