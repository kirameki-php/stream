<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Kirameki\Core\Exceptions\RuntimeException;
use function count;
use function error_clear_last;
use function error_get_last;
use function tempnam;
use function unlink;

class RandomFileStream extends FileStream
{
    public function __construct(
        string $prefix = 'kirameki',
        string $dir = '/tmp',
        protected bool $persist = true,
    )
    {
        $uri = @tempnam($dir, $prefix);
        $error = error_get_last() ?? [];
        if ($uri === false || count($error) > 0) {
            $message = $error['message'] ?? 'unknown';
            if ($message === "tempnam(): file created in the system's temporary directory") {
                error_clear_last();
                throw new RuntimeException("Could not create file at {$dir}", [
                    'dir' => $dir,
                    'prefix' => $prefix,
                    'error' => $error,
                ]);
            }
            $this->throwLastError();
        }

        parent::__construct($uri);
    }

    public function close(): bool
    {
        $result = parent::close();

        if (!$this->persist) {
            unlink($this->getUri());
        }

        return $result;
    }
}
