<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function tmpfile;

class TmpFileStream extends ResourceStreamable
{
    use CanRead;
    use CanWrite;
    use CanSeek;

    public function __construct()
    {
        if (($tmp = tmpfile()) === false) {
            $this->throwLastError();
        }
        parent::__construct($tmp);
    }
}
