<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fclose;
use function flock;
use function fopen;
use function is_resource;

abstract class Stream
{
    use ThrowsError;

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
        $stream = @fopen($uri, $mode);
        if ($stream === false) {
            $this->throwLastError();
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
     * @return void
     */
    public function unlock(): void
    {
        $result = @flock($this->stream, LOCK_UN);
        if ($result === false) {
            $this->throwLastError();
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
