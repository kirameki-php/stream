<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class FileWriter extends FileStreamable implements StreamWritable
{
    use CanClose;
    use CanWrite;

    /**
     * @param string $path
     * @param bool $append
     */
    public function __construct(
        string $path,
        bool $append = false,
    )
    {
        parent::__construct($path, $append ? 'ab' : 'wb');
    }
}
