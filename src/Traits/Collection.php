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

    /**
     * Define the collection data
     */
    public function define(array $list)
    {
        $this->list = $list;
    }

    /**
     * @see @offsetSet
     */
    public function add($value, $key = null): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Callback to the set value
     */
    protected function set(mixed $value, $offset = null)
    {
        return $value;
    }

    /**
     * Returns results of the collection
     */
    public function toArray(): array
    {
        return $this->list;
    }

    /**
     * Filters elements of the collection using a callback function
     */
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

    /**
     * Counts all elements in the collection
     */
    public function count(): int
    {
        return count($this->list);
    }

    /**
     * Applies the callback to the elements of the collection
     */
    public function map(Callable $callable)
    {
        return array_map($callable, $this->list);
    }

    /**
     * Returns the value at specified offset.
     * This method is executed when checking if offset is empty().
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->list[$offset] ?? null;
    }

    /**
     * Assigns a value to the specified offset.
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
     * Whether or not an offset exists.
     * This method is executed when using isset() or empty().
     */
    public function offsetExists($offset): bool
    {
        return isset($this->list[$offset]);
    }

    /**
     * Unsets an offset.
     */
    public function offsetUnset($offset): void
    {
        unset($this->list[$offset]);
    }
}
