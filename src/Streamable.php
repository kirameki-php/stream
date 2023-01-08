<?php declare(strict_types=1);

namespace SouthPointe\Stream;

interface Streamable
{
    /**
     * @return resource
     */
    public function getResource(): mixed;
}
