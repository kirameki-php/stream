<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Kirameki\Core\Exceptions\RuntimeException;
use function error_get_last;
use function tempnam;

class TmpFileStream extends FileStream
{
    public function __construct(
        string $dir = '/tmp',
        string $prefix = 'kirameki',
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

        parent::__construct($uri, $persist);
    }
}
