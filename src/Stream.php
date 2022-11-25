<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Closure;
use RuntimeException;
use function error_get_last;
use function fclose;
use function flock;
use function fopen;
use function is_resource;
use function json_encode;
use const JSON_THROW_ON_ERROR;

abstract class Stream
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * @param string $file
     * @param string $mode
     */
    public function __construct(
        protected readonly string $file,
        protected readonly string $mode,
    )
    {
        $stream = fopen($file, $mode);
        if ($stream === false) {
            throw new RuntimeException(json_encode(error_get_last(), JSON_THROW_ON_ERROR));
        }
        $this->stream = $stream;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->file;
    }

    /**
     * @param bool $blocking
     * @return bool
     */
    abstract public function lock(bool $blocking = true): bool;

    /**
     * @return bool
     */
    public function unlock(): bool
    {
        return flock($this->stream, LOCK_UN);
    }

    /**
     * @template TReturn
     * @param Closure(static): TReturn $call
     * @return TReturn
     */
    public function withLock(Closure $call): mixed
    {
        try {
            $this->lock();
            return $call($this);
        }
        finally {
            $this->unlock();
        }
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return is_resource($this->stream);
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return ! $this->isOpen();
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return fclose($this->stream);
    }
}
