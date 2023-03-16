<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fopen;

abstract class FileStreamable extends ResourceStreamable
{
    use ThrowsError;

    public function __construct(
        string $path,
        string $mode = 'r+b',
    )
    {
        parent::__construct($this->openResource($path, $mode));
    }

    /**
     * @return resource
     */
    protected function openResource(string $path, string $mode)
    {
        $stream = @fopen($path, $mode);
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
        return $this->meta['uri'];
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->meta['mode'];
    }
}
