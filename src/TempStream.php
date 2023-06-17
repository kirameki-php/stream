<?php declare(strict_types=1);

namespace Kirameki\Stream;

class TempStream extends FileStream
{
    /**
     * @param int $maxMemory
     * [Optional] Defaults to **null** (2MiB).
     */
    public function __construct(int $maxMemory = null)
    {
        $path = 'php://temp';

        if ($maxMemory !== null) {
            $path .= "/maxmemory:{$maxMemory}";
        }

        parent::__construct($path, 'w+b');
    }
}
