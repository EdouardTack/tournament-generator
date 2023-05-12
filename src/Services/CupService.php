<?php

namespace Tackacoder\Tournament\Services;

use Tackacoder\Tournament\Supports\ServiceInterface;

class CupService implements ServiceInterface
{
    protected string $name = 'cup';

    public function generate(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }}