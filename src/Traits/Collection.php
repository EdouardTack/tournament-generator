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

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->list;
    }

    public function offsetGet($offset) {
        return isset($this->list[$offset]) ? $this->list[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->list[] = $value;
        } else {
            $this->list[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->list[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->list[$offset]);
    }
}
