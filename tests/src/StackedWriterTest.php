<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\MemoryStream;
use Kirameki\Stream\StackedWriter;
use Kirameki\Stream\TempStream;

class StackedWriterTest extends TestCase
{
    public function test_write(): void
    {
        $stream1 = new MemoryStream();
        $stream2 = new MemoryStream();
        $stacked = new StackedWriter($stream1, $stream2);

        $stacked->write('ab');

        $this->assertSame('ab', $stream1->readFromStartToEnd());
        $this->assertSame('ab', $stream2->readFromStartToEnd());
    }
}
