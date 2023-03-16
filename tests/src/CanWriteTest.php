<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileReader;
use SouthPointe\Stream\FileStream;
use SouthPointe\Stream\FileWriter;
use SouthPointe\Stream\MemoryStream;
use SouthPointe\Stream\TmpFileStream;
use function dump;
use function sleep;
use function str_repeat;

class CanWriteTest extends TestCase
{
    public function test_truncate_without_args(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->truncate();

        self::assertSame('', $stream->readToEnd());
    }

    public function test_truncate_with_size(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->truncate(1)->rewind();

        self::assertSame('a', $stream->readToEnd());
    }
}
