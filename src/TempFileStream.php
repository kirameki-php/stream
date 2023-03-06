<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Kirameki\Core\Exceptions\RuntimeException;
use function error_get_last;
use function tempnam;
use function unlink;

class TempFileStream extends Stream implements StreamReadable, StreamWritable
{
    use CanRead;
    use CanWrite;
    use CanSeek;
    use CanClose {
        close as _close;
    }

    public function __construct(
        string $dir = '/tmp',
        string $prefix = '',
        protected bool $deleteOnClose = true,
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
        parent::__construct($uri, 'rw+');
    }

    public function close(): bool
    {
        $result = $this->_close();

        if ($this->deleteOnClose) {
            unlink($this->getFilePath());
        }

        return $result;
    }
}
