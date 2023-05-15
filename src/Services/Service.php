<?php

namespace Tackacoder\Tournament\Services;

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

    public function getConfig(string $key): mixed
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * 
     */
    protected function event($args)
    {
        if (is_callable($this->getCallable())) {
            call_user_func($this->getCallable(), $args);
        }
    }
}