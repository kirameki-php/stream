<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function stream_get_meta_data;

abstract class ResourceStreamable implements Streamable
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var array<string, mixed>
     */
    protected readonly array $meta;

    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->meta = stream_get_meta_data($this->resource);
    }

    /**
     * @return resource
     */
    public function getResource(): mixed
    {
        return $this->resource;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMetaData(): array
    {
        return $this->meta;
    }
}
