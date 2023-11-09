<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use ErrorException;
use Kirameki\Stream\FileReader;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;

class FileReaderTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertFalse($stream->isEof());
        $this->assertTrue($stream->isOpen());
        $this->assertSame('rb', $stream->getMode());
    }

    #[WithoutErrorHandler]
    public function test_with_no_such_file(): void
    {
        $file = 'tests/samples/invalid.txt';
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("fopen({$file}): Failed to open stream: No such file or directory");
        new FileReader($file);
    }
}
