<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class Stderr extends Stream implements StreamWritable
{
    use CanWrite;

    public function __construct()
    {
        parent::__construct('php://stderr', 'w');
    }
}
