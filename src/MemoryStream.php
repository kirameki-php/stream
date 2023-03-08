<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class MemoryStream extends Stream implements StreamReadable, StreamWritable, StreamSeekable
{
    use CanRead;
    use CanWrite;
    use CanSeek;
    use CanClose;

    public function __construct()
    {
        parent::__construct('php://memory', 'rw+');
    }
}
