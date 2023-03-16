<?php declare(strict_types=1);

namespace SouthPointe\Stream;

abstract class ResourceStreamable implements Streamable
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return resource
     */
    public function getResource(): mixed
    {
        return $this->resource;
    }
}
