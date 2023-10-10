<?php declare(strict_types=1);

namespace Kirameki\Stream;

use function array_values;

class StackedWriter implements StreamWritable
{
    /**
     * @var list<StreamWritable>
     */
    protected array $stack;

    /**
     * @param StreamWritable ...$stack
     */
    public function __construct(StreamWritable ...$stack)
    {
        $this->stack = array_values($stack);
    }

    public function write(string $data, ?int $length = null): int
    {
        $bytesWritten = 0;
        foreach ($this->stack as $writer) {
            $bytesWritten += $writer->write($data, $length);
        }
        return $bytesWritten;
    }
}
