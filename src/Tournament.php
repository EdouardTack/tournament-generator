<?php

namespace Tackacoder\Tournament;

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Services\ServicesCollection;
use Tackacoder\Tournament\Supports\ServiceInterface;
use ReflectionClass;

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
     * @var ServicesCollection
     */
    protected ServicesCollection $services;

    /**
     * @constructor
     */
    public function __construct(protected readonly string $name, protected readonly string $mode)
    {
        $this->date = CarbonImmutable::now();
        $this->services = new ServicesCollection();
    }

    /**
     * Add a service tournament generator to the collection
     * 
     * @param ServiceInterface $service
     * @return Tournament
     */
    public function addService(ServiceInterface $service): Tournament
    {
        $this->services[(new ReflectionClass($service))->getShortName()] = $service;

        return $this;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services->toArray();
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