<?php

namespace Tackacoder\Tournament\Services;

use Closure;

class Service
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var string
     */
    protected string $name = 'service';

    /**
     * @var \Closure
     */
    protected $callable = null;

    public function setConfig(array $config): Service
    {
        $this->config = $config;

        return $this;
    }

    public function getConfig(string $key, $default = null): mixed
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $default;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCallable(): ?Closure
    {
        return $this->callable;
    }

    /**
     * Call a closure function with args
     * It can be used for event Listener packages
     */
    protected function event(array $args): void
    {
        if (is_callable($this->getCallable())) {
            call_user_func($this->getCallable(), $args);
        }
    }
}