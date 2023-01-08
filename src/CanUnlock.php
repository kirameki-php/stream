<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function flock;
use const LOCK_UN;

trait CanUnlock
{
    use ThrowsError;

    /**
     * @return resource
     */
    abstract public function getStream(): mixed;

    /**
     * @return void
     */
    public function unlock(): void
    {
        $result = @flock($this->getStream(), LOCK_UN);
        if ($result === false) {
            $this->throwLastError();
        }
    }
}
