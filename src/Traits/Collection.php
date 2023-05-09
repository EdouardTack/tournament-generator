<?php

namespace Tackacoder\Tournament\Traits;

trait Collection
{
    /**
     * List of all availables services
     * 
     * @var array
     */
    protected array $list = [];

    public function toArray(): array
    {
        return $this->list;
    }

    /**
     * 
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->list[$offset] ?? null;
    }

    /**
     * 
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->list[] = $value;
        } else {
            $this->list[$offset] = $value;
        }
    }

    /**
     * 
     */
    public function offsetExists($offset): bool
    {
        return isset($this->list[$offset]);
    }

    /**
     * 
     */
    public function offsetUnset($offset): void
    {
        unset($this->list[$offset]);
    }
}
