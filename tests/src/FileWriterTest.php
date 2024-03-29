<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\FileReader;
use Kirameki\Stream\FileWriter;

class FileWriterTest extends TestCase
{
    public function test_construct(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        $this->assertTrue($stream->isOpen());
    }

    public function test_write_on_append(): void
    {
        $file = 'tests/samples/append.txt';
        $streamWrite = new FileWriter($file, true);
        $streamWrite->write('b');
        try {
            $streamRead = new FileReader($file);
            $this->assertNotSame('ab', $streamRead->read(5));
        } finally {
            $streamWrite->truncate(7);
        }
    }
}
