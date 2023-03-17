<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use ErrorException;
use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\FileWriter;

class FileWriterTest extends TestCase
{
    public function test_construct(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        self::assertTrue($stream->isOpen());
    }

    public function test_with_permission(): void
    {
        $file = 'tests/samples/permission_denied.txt';
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("fopen({$file}): Failed to open stream: Permission denied");
        new FileWriter($file);
    }

    public function test_getFilePath(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        self::assertSame($file, $stream->getUri());
    }

    public function test_write_on_append(): void
    {
        $file = 'tests/samples/append.txt';
        $streamWrite = new FileWriter($file, true);
        $streamWrite->write('b');
        try {
            $streamRead = new FileReader($file);
            self::assertNotSame('ab', $streamRead->read(5));
        } finally {
            $streamWrite->truncate(7);
        }
    }
}
