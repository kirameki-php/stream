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
     * @param string $uri
     * @param string $mode
     */
    public function __construct(
        protected readonly string $uri,
        protected readonly string $mode,
    )
    {
        $stream = fopen($uri, $mode);
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
        return $this->uri;
    }

    /**
     * @return resource
     */
    public function getStream(): mixed
    {
        return $this->stream;
    }

    /**
     * @return bool
     */
    public function unlock(): bool
    {
        return flock($this->stream, LOCK_UN);
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
