<?php declare(strict_types=1);

namespace SouthPointe\Core;

trait WithContext
{
    /**
     * @param array<int|string, mixed>|null $context
     */
    protected ?array $context = null;

    /**
     * @inheritDoc
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @param iterable<int|string, mixed>|null $context
     * @return $this
     */
    public function setContext(?iterable $context): static
    {
        if ($context === null) {
            $this->context = null;
            return $this;
        }

        $this->context = [];
        foreach ($context as $key => $val) {
            $this->addContext($key, $val);
        }

        return $this;
    }

    /**
     * @param int|string $key
     * @param mixed $val
     * @return $this
     */
    public function addContext(int|string $key, mixed $val): static
    {
        $this->context ??= [];
        $this->context[$key] = $val;
        return $this;
    }

    /**
     * @param iterable<int|string, mixed> $context
     * @return $this
     */
    public function mergeContext(iterable $context): static
    {
        foreach ($context as $key => $val) {
            $this->addContext($key, $val);
        }
        return $this;
    }
}
