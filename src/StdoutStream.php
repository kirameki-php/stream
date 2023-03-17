<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class StdoutStream extends ResourceStreamable implements StreamWritable
{
    use CanWrite;

    public function __construct()
    {
        parent::__construct($this->open('php://stdout', 'w'));
    }
}
