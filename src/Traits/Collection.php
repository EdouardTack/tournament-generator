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
     * @var array
     */
    protected array $finder;

    public function define(array $list)
    {
        $this->list = $list;
    }

    public function add($value, $key = null): void
    {
        $this->offsetSet($key, $value);
    }

    public function set(mixed $value, $offset = null)
    {
        return $value;
    }

    public function toArray(): array
    {
        return $this->list;
    }

    public function find(array $finder)
    {
        $this->finder = $finder;

        return current(array_filter($this->list, function ($value) {
            foreach ($this->finder as $method => $find) {
                $methodName = 'get' . ucfirst($method);
                if (method_exists($value, $methodName)) {
                    return call_user_func([$value, $methodName]) === $find;
                }
            }

            return false;
        }));
    }

    public function count(): int
    {
        return count($this->list);
    }

    public function map(Callable $callable)
    {
        return array_map($callable, $this->list);
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
            $this->list[] = $this->set($value, $offset);
        } else {
            $this->list[$offset] = $this->set($value, $offset);
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
