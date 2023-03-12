<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class FileStream extends Stream implements StreamReadable, StreamWritable, StreamSeekable
{
    use CanRead;
    use CanWrite;
    use CanSeek;
    use CanClose;

    public function __construct(
        string $path,
        bool $append = false,
    )
    {
        parent::__construct($path, $append ? 'ab+' : 'rb+');
    }
}
