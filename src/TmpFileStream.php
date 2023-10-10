<?php declare(strict_types=1);

namespace Kirameki\Stream;

use function assert;
use function tmpfile;

class TmpFileStream extends ResourceStreamable implements StreamLockable, StreamReadable, StreamWritable, StreamSeekable
{
    use CanScan;
    use CanWrite;
    use CanLock;

    public function __construct()
    {
        $tmp = tmpfile();
        assert($tmp !== false);
        parent::__construct($tmp);
    }
}
