<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class FileStream extends ResourceStreamable implements StreamReadable, StreamWritable, StreamSeekable
{
    use CanRead;
    use CanWrite;
    use CanSeek;

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
