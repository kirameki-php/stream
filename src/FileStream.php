<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fopen;

class FileStream extends ResourceStreamable implements StreamReadable, StreamWritable, StreamSeekable
{
    use CanRead;
    use CanWrite;
    use CanSeek;

    public function __construct(
        string $path,
        string $mode = 'r+b',
    )
    {
        parent::__construct($this->open($path, $mode));
    }
}
