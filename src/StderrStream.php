<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class StderrStream extends Stream implements StreamWritable
{
    use CanWrite;

    public function __construct()
    {
        parent::__construct('php://stderr', 'wb');
    }
}
