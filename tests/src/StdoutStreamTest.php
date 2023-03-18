<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\StdoutStream;

class StdoutStreamTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new StdoutStream();
        self::assertTrue($stream->isOpen());
        self::assertSame('php://stdout', $stream->getUri());
        self::assertSame('w', $stream->getMode());
        $stream->close();
    }
}
