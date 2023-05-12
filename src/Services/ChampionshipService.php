<?php

namespace Tackacoder\Tournament\Services;

use Tackacoder\Tournament\Supports\ServiceInterface;

class ChampionshipService implements ServiceInterface
{
    protected string $name = 'championship';

    public function generate(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }
}