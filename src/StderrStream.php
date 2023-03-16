<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class StderrStream extends FileStreamable implements StreamWritable
{
    use CanWrite;

    public function __construct()
    {
        parent::__construct('php://stderr', 'w');
    }
}
