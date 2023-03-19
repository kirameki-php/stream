<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Kirameki\Core\Exceptions\RuntimeException;
use function assert;
use function escapeshellarg;
use function exec;
use function str_repeat;
use function unlink;

class RandomFileStream extends FileStream
{
    /**
     * @param string $dir
     * @param string $prefix
     * @param bool $persist
     */
    public function __construct(
        string $dir = '/tmp',
        string $prefix = 'kirameki',
        public readonly bool $persist = true,
    )
    {
        $basename = escapeshellarg($prefix . '.' . str_repeat('X', 10));
        $dir = escapeshellarg($dir);
        $command = "mktemp --tmpdir={$dir} {$basename} 2>&1";
        $result = exec($command, $output, $code);
        if ($code !== 0) {
            throw new RuntimeException($output[0], [
                'command' => $command,
                'output' => $output,
                'code' => $code,
            ]);
        }
        assert($result);
        parent::__construct($result);
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        $result = parent::close();

        if (!$this->persist) {
            unlink($this->getUri());
        }

        return $result;
    }
}
