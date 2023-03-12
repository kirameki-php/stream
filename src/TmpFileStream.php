<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Kirameki\Core\Exceptions\RuntimeException;
use function error_get_last;
use function tempnam;
use function unlink;

class TmpFileStream extends FileStream
{
    public function __construct(
        string $prefix = 'kirameki',
        string $dir = '/tmp',
        protected bool $persist = true,
    )
    {
        $uri = @tempnam($dir, $prefix);

        if ($uri === false) {
            $lastError = error_get_last() ?? [];
            $errorMessage = $lastError['message'] ?? 'unknown';
            if ($errorMessage === "tempnam(): file created in the system's temporary directory") {
                throw new RuntimeException("Could not create file at {$dir}", [
                    'dir' => $dir,
                    'prefix' => $prefix,
                    'error' => $lastError,
                ]);
            }
            $this->throwLastError();
        }

        parent::__construct($uri);
    }

    public function close(): bool
    {
        $result = parent::close();

        if ($this->persist) {
            unlink($this->getFilePath());
        }

        return $result;
    }
}
