<?php

namespace Tackacoder\Tournament\Services;

use Tackacoder\Tournament\Services\Service;
use Tackacoder\Tournament\Supports\ServiceInterface;

class CupService extends Service implements ServiceInterface
{
    protected string $name = 'cup';

    public function generate(array $config): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }
}