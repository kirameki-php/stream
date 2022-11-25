<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class Stdout extends StreamWriter
{
    public function __construct()
    {
        parent::__construct('php://stdout');
    }
}
