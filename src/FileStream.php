<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function unlink;

class FileStream extends Stream implements StreamReadable, StreamWritable, StreamSeekable
{
    use CanRead;
    use CanWrite;
    use CanSeek;
    use CanClose {
        close as protected _close;
    }

    public function __construct(
        string $path,
        protected bool $persist = true,
    )
    {
        parent::__construct($path, 'rwb');
    }

    public function close(): bool
    {
        $result = $this->_close();

        if ($this->persist) {
            unlink($this->getFilePath());
        }

        return $result;
    }
}
