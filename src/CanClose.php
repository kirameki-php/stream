<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fclose;
use function is_resource;

trait CanClose
{
    use ThrowsError;

    /**
     * @return resource
     */
    abstract public function getStream(): mixed;

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return is_resource($this->getStream());
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return ! $this->isOpen();
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return fclose($this->getStream());
    }
}
