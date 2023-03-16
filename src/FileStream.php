<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fopen;

class FileStream extends FileStreamable implements StreamReadable, StreamWritable, StreamSeekable
{
    use CanRead;
    use CanWrite;
    use CanSeek;
    use CanClose;
}
