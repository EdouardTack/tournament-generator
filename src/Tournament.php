<?php

namespace Tackacoder\Tournament;

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Collections\ServicesCollection;
use Tackacoder\Tournament\Collections\TeamsCollection;
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
     * @var TeamsCollection
     */
    protected TeamsCollection $teams;

    /**
     * @constructor
     */
    public function __construct(protected readonly string $name, protected readonly string $mode)
    {
        $this->date = CarbonImmutable::now();
        $this->services = new ServicesCollection();
        $this->teams = new TeamsCollection();
    }

    /**
     * Add a service tournament generator to the collection
     */
    public function addService(ServiceInterface $service): Tournament
    {
        $this->services[(new ReflectionClass($service))->getShortName()] = $service;

        return $this;
    }

    public function getServices(): array
    {
        return $this->services->toArray();
    }

    public function setTeams(array $teams): Tournament
    {
        $this->teams->define($teams);

        return $this;
    }

    public function addTeam(array $team): Tournament
    {
        $this->teams[] = $team;

        return $this;
    }

    public function getTeams(): array
    {
        return $this->teams->toArray();
    }

    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getName(): string
    {
        return $this->name;
    }
}