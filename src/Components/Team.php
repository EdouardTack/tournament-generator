<?php

namespace Tackacoder\Tournament\Components;

use Exception;

class Team
{
    protected ?int $id;

    protected string $uuid;
    
    protected string $name;

    protected bool $status;

    public function __construct(array $team)
    {
        if (!is_string($team['name'])) {
            throw new Exception("Team name must be string data !");
        }

        if (isset($team['status']) && !is_bool($team['status'])) {
            throw new Exception("Team status must be boolean data !");
        }

        $this->name = $team['name'];
        $this->status = $team['status'] ?? false;

        $this->id = (isset($team['id']) && is_int($team['id'])) ? (int) $team['id'] :  null;
        $this->uuid = (isset($team['uuid']) && is_string($team['uuid'])) ? $team['uuid'] : substr(bin2hex(random_bytes(6)), 0, 16);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
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