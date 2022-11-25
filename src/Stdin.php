<?php declare(strict_types=1);

namespace SouthPointe\Stream;

class Stdin extends StreamReader
{
    public function __construct()
    {
        parent::__construct('php://stdin');
    }
}
