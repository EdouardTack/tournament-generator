<?php

namespace Tackacoder\Tournament\Components;

use Exception;

class Team
{
    protected string $uuid;
    
    protected string $name;

    protected bool $status;

    public function __construct(array $team)
    {
        if (!is_string($team['name'])) {
            throw new Exception("Team name must be string data !");
        }

        if (!is_bool($team['status'])) {
            throw new Exception("Team status must be boolean data !");
        }

        $this->name = $team['name'];
        $this->status = $team['status'] ?? false;

        $this->uuid = substr(bin2hex(random_bytes(6)), 0, 15);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}