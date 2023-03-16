<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class MemoryStream extends FileStream
{
    public function __construct()
    {
        parent::__construct('php://memory', 'w+b');
    }
}
