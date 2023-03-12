<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class StdinStream extends Stream implements StreamReadable
{
    use CanRead;

    public function __construct()
    {
        parent::__construct('php://stdin', 'r');
    }
}
