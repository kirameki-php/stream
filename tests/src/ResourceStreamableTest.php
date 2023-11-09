<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\Exceptions\StreamErrorException;
use Kirameki\Stream\FileStream;
use Kirameki\Stream\MemoryStream;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use TypeError;
use function str_repeat;
use function uniqid;
use function unlink;

class ResourceStreamableTest extends TestCase
{
    public function test_open(): void
    {
        $path = uniqid('/tmp/open-') . '.txt';
        $stream = new FileStream($path);
        $this->assertTrue($stream->isOpen());
        $this->assertFileExists($path);
        unlink($path);
    }

    #[WithoutErrorHandler]
    public function test_open_long_path(): void
    {
        // file name limit is 256
        $path = '/tmp/open-' . str_repeat('a', 251);

        $this->expectException(StreamErrorException::class);
        $this->expectExceptionMessage("fopen($path): Failed to open stream: Filename too long");

        $stream = new FileStream($path);
        $stream->close();
    }

    public function test_getResource(): void
    {
        $stream = new MemoryStream();
        $this->assertIsResource($stream->getResource());
    }

    public function test_getMetaData(): void
    {
        $stream = new MemoryStream();
        $meta = $stream->getMetadata();
        $this->assertSame([
            'timed_out' => false,
            'blocked' => true,
            'eof' => false,
            'wrapper_type' => 'PHP',
            'stream_type' => 'MEMORY',
            'mode' => 'w+b',
            'unread_bytes' => 0,
            'seekable' => true,
            'uri' => 'php://memory',
        ], $meta);
    }

    public function test_getFilePath(): void
    {
        $stream = new MemoryStream();
        $this->assertSame('php://memory', $stream->getUri());
        // can return info even after closed.
        $stream->close();
        $this->assertSame('php://memory', $stream->getUri());
    }

    public function test_getMode(): void
    {
        $stream = new MemoryStream();
        $this->assertSame('w+b', $stream->getMode());
        // can return info even after closed.
        $stream->close();
        $this->assertSame('w+b', $stream->getMode());
    }

    public function test_isOpen(): void
    {
        $stream = new MemoryStream();
        $this->assertTrue($stream->isOpen());
        $stream->close();
        $this->assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new MemoryStream();
        $this->assertFalse($stream->isClosed());
        $stream->close();
        $this->assertTrue($stream->isClosed());
    }

    public function test_isEof(): void
    {
        $stream = new MemoryStream();
        $this->assertFalse($stream->isEof());
        // reading once allows eof flag to be set.
        $this->assertSame('', $stream->read(1));
        $this->assertTrue($stream->isEof());
    }

    public function test_isNotEof(): void
    {
        $stream = new MemoryStream();
        $this->assertTrue($stream->isNotEof());
        // reading once allows eof flag to be set.
        $this->assertSame('', $stream->read(1));
        $this->assertFalse($stream->isNotEof());
    }

    public function test_close(): void
    {
        $stream = new MemoryStream();
        $stream->close();
        $this->assertTrue($stream->isClosed());
        $this->assertFalse($stream->isOpen());
    }

    public function test_close_after_close(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('fclose(): supplied resource is not a valid stream resource');
        $stream = new MemoryStream();
        $stream->close();
        $stream->close();
    }

    public function test___debugInfo(): void
    {
        $stream = new MemoryStream();
        $this->assertSame(
            ['uri' => 'php://memory', 'mode' => 'w+b'],
            $stream->__debugInfo(),
        );
    }
}
