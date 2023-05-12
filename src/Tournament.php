<?php

namespace Tackacoder\Tournament;

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Collections\ServicesCollection;
use Tackacoder\Tournament\Collections\TeamsCollection;
use Tackacoder\Tournament\Services\ChampionshipService;
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
    public function __construct(protected string $name, protected string $mode = 'championship')
    {
        $this->date = CarbonImmutable::now();
        $this->services = new ServicesCollection();
        $this->services->add(new ChampionshipService());
        $this->teams = new TeamsCollection();
    }

    /**
     * Generate the Service generate 
     */
    public function generate(): array
    {
        $service = $this->services->find(['name' => $this->mode]);

        if (empty($service)) {
            throw new \Exception("No service found for {$this->mode} !");
        }

        return $service->generate();
    }

    /**
     * Add a service tournament generator to the collection
     */
    public function addService(ServiceInterface $service): Tournament
    {
        $this->services->add($service, $service->getName());

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
        $this->teams->add($team);

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

    public function setMode(string $mode): Tournament
    {
        $this->mode = $mode;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Tournament
    {
        $this->name = $name;

        return $this;
    }
}