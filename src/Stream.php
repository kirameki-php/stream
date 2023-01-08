<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fopen;

abstract class Stream implements Streamable
{
    use ThrowsError;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @param string $uri
     * @param string $mode
     */
    public function __construct(
        protected readonly string $uri,
        protected readonly string $mode,
    )
    {
        $this->resource = $this->openResource();
    }

    /**
     * @return resource
     */
    protected function openResource()
    {
        $stream = @fopen($this->uri, $this->mode);
        if ($stream === false) {
            $this->throwLastError();
        }
        return $stream;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return resource
     */
    public function getResource(): mixed
    {
        return $this->resource;
    }
}
