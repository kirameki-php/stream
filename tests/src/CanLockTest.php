<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\FileWriter;
use TypeError;

class CanLockTest extends TestCase
{
    public function test_exclusiveLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        self::assertTrue($stream1->exclusiveLock());
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->exclusiveLock());
        self::assertTrue($stream2->unlock());
    }

    public function test_exclusiveLock_after_close(): void
    {
        $this->expectExceptionMessage('flock(): supplied resource is not a valid stream resource');
        $this->expectException(TypeError::class);
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertTrue($stream->close());
        $stream->exclusiveLock();
    }

    public function test_withExclusiveLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream1 = new FileWriter($file);
        $stream2 = new FileWriter($file);
        $stream1->withExclusiveLock(function() use ($stream2) {
            self::assertFalse($stream2->exclusiveLock(false));
        });
        self::assertTrue($stream1->unlock());
        self::assertTrue($stream2->unlock());
    }

    public function test_unlock_exclusiveLock(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileWriter($file);
        self::assertTrue($stream->exclusiveLock());
        self::assertTrue($stream->unlock());
    }

    public function test_unlock_exclusiveLock_after_close(): void
    {
        $this->expectExceptionMessage('flock(): supplied resource is not a valid stream resource');
        $this->expectException(TypeError::class);
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertTrue($stream->exclusiveLock());
        self::assertTrue($stream->close());
        $stream->unlock();
    }

    public function test_unlock_exclusiveLock_without_locking(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        $stream->sharedLock();
        self::assertTrue($stream->unlock());
        self::assertTrue($stream->close());
    }
}
