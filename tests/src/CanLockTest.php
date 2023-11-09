<?php declare(strict_types=1);

namespace Tests\Kirameki\Stream;

use Kirameki\Stream\FileReader;
use Kirameki\Stream\FileWriter;
use TypeError;

class CanLockTest extends TestCase
{
    public function test_exclusiveLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        $this->assertTrue($stream1->exclusiveLock());
        $this->assertTrue($stream1->unlock());
        $this->assertTrue($stream2->exclusiveLock());
        $this->assertTrue($stream2->unlock());
    }

    public function test_exclusiveLock_after_close(): void
    {
        $this->expectExceptionMessage('flock(): supplied resource is not a valid stream resource');
        $this->expectException(TypeError::class);
        $stream = new FileWriter('tests/samples/write.txt');
        $this->assertTrue($stream->close());
        $stream->exclusiveLock();
    }

    public function test_withExclusiveLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        $stream1->withExclusiveLock(function() use ($stream2) {
            $this->assertFalse($stream2->exclusiveLock(false));
        });
        $this->assertTrue($stream1->unlock());
        $this->assertTrue($stream2->unlock());
    }

    public function test_sharedLock(): void
    {
        $file = 'tests/samples/read.txt';
        $stream1 = new FileReader($file);
        $stream2 = new FileReader($file);
        $this->assertTrue($stream1->sharedLock());
        $this->assertTrue($stream2->sharedLock());
        $this->assertTrue($stream1->unlock());
        $this->assertTrue($stream2->unlock());
    }

    public function test_withSharedLock(): void
    {
        $file = 'tests/samples/read.txt';
        $stream1 = new FileReader($file);
        $stream2 = new FileReader($file);
        $stream1->withSharedLock(function() use ($stream2) {
            $this->assertTrue($stream2->sharedLock());
        });
        $this->assertTrue($stream1->unlock());
        $this->assertTrue($stream2->unlock());
    }

    public function test_withExAndShLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileReader($file);
        $stream1->withExclusiveLock(function() use ($stream2) {
            $this->assertFalse($stream2->sharedLock(false));
        });
        $this->assertTrue($stream1->unlock());
        $this->assertTrue($stream2->unlock());
    }

    public function test_unlock_exclusiveLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        $this->assertTrue($stream->exclusiveLock());
        $this->assertTrue($stream->unlock());
    }

    public function test_unlock_exclusiveLock_after_close(): void
    {
        $this->expectExceptionMessage('flock(): supplied resource is not a valid stream resource');
        $this->expectException(TypeError::class);
        $stream = new FileWriter('tests/samples/write.txt');
        $this->assertTrue($stream->exclusiveLock());
        $this->assertTrue($stream->close());
        $stream->unlock();
    }

    public function test_unlock_exclusiveLock_without_locking(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        $this->assertTrue($stream->sharedLock());
        $this->assertTrue($stream->unlock());
        $this->assertTrue($stream->close());
    }

    public function test_unlock_on_sharedLock(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        $this->assertTrue($stream->unlock());
        $this->assertTrue($stream->sharedLock());
        $this->assertTrue($stream->unlock());
    }
}
