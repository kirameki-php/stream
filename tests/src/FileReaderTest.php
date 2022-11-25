<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileReader;

class FileReaderTest extends TestCase
{
    public function test_readLine(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        // nothing is read
        self::assertFalse($stream->readLine(1));
        // specify length
        self::assertSame('1', $stream->readLine(2));
        // read to end
        self::assertSame("23\n", $stream->readLine());
        // over read
        self::assertFalse($stream->readLine());
    }

    public function test_readLine_on_dir(): void
    {
        $this->expectError();
        $this->expectErrorMessage('fread(): Read of 8192 bytes failed with errno=21 Is a directory');
        $stream = new FileReader('tests/samples/');
        $stream->readToEnd();
    }

    public function test_readToEnd(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readToEnd());
    }

    public function test_readToEnd_with_buffer(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertSame("123\n", $stream->readToEnd(1));
    }
}
