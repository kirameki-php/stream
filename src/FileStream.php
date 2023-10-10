<?php declare(strict_types=1);

namespace Kirameki\Stream;

class FileStream extends ResourceStreamable implements StreamLockable, StreamReadable, StreamWritable, StreamSeekable
{
    use CanScan;
    use CanWrite;
    use CanLock;

    /**
     * @param string $path
     * @param string $mode
     */
    public function __construct(
        string $path,
        string $mode = 'c+b',
    )
    {
        parent::__construct($this->open($path, $mode));
    }
}
