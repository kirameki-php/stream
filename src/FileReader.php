<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class FileReader extends FileStreamable implements StreamReadable, StreamSeekable
{
    use CanClose;
    use CanRead;
    use CanSeek;

    /**
     * @param string $path
     */
    public function __construct(
        string $path,
    )
    {
        parent::__construct($path, 'rb');
    }
}
