<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fopen;

abstract class FileStreamable extends ResourceStreamable
{
    use ThrowsError;

    public function __construct(
        protected readonly string $path,
        protected readonly string $mode = 'rb+',
    )
    {
        parent::__construct($this->openResource());
    }

    /**
     * @return resource
     */
    protected function openResource()
    {
        $stream = @fopen($this->path, $this->mode);
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
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}
