<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class FileWriter extends Stream implements StreamWritable
{
    use CanWrite;

    /**
     * @param string $file
     * @param bool $append
     */
    public function __construct(
        string $file,
        bool $append = false,
    )
    {
        parent::__construct($file, $append ? 'a' : 'w');
    }
}
