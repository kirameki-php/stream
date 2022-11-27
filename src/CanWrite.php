<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Closure;
use function fflush;
use function flock;
use function fwrite;
use const LOCK_EX;
use const LOCK_NB;

trait CanWrite
{
    use ThrowsError;

    /**
     * @return resource
     */
    abstract public function getStream(): mixed;

    /**
     * @param string $data
     * @param int<0, max>|null $length
     * @return int
     */
    public function write(string $data, ?int $length = null): int
    {
        $bytesWritten = fwrite($this->getStream(), $data, $length);
        if ($bytesWritten === false) {
            $this->throwLastError();
        }
        return $bytesWritten;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $result = fflush($this->getStream());
        if ($result === false) {
            $this->throwLastError();
        }
    }

    /**
     * @param bool $blocking
     * @return bool
     */
    public function exclusiveLock(bool $blocking = true): bool
    {
        return flock(
            $this->stream,
            $blocking ? LOCK_EX : LOCK_EX | LOCK_NB
        );
    }

    /**
     * @template TReturn
     * @param Closure(static): TReturn $call
     * @return TReturn
     */
    public function withExclusiveLock(Closure $call): mixed
    {
        try {
            $this->exclusiveLock();
            return $call($this);
        } finally {
            $this->unlock();
        }
    }
}
