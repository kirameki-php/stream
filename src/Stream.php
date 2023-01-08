<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fopen;

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
}
