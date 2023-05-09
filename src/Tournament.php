<?php

namespace Tackacoder\Tournament;

use Carbon\CarbonImmutable;

/**
 * 
 */
class Tournament
{
    /**
     * @var CarbonImmutable
     */
    protected CarbonImmutable $date;

    /**
     * @constructor
     */
    public function __construct(protected readonly string $name, protected readonly string $mode)
    {
        $this->date = CarbonImmutable::now();
    }

    /**
     * @return CarbonImmutable
     */
    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}