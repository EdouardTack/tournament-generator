<?php

namespace Tackacoder\Tournament;

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Collections\ServicesCollection;
use Tackacoder\Tournament\Collections\TeamsCollection;
use Tackacoder\Tournament\Helpers\SerializerEncoder;
// use Tackacoder\Tournament\Services\ChampionshipService;
use Tackacoder\Tournament\Supports\ServiceInterface;

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
    public function __construct(protected string $name, protected string $mode = 'championship', string $utc = 'UTC')
    {
        $this->setDate(utc: $utc);
        $this->services = new ServicesCollection();
        // Do we include this service by default ?
        // $this->addService(new ChampionshipService());
        $this->teams = new TeamsCollection();
    }

    /**
     * Generate the Service generator method
     */
    public function generate(array $config = [], bool $hasArray = false): array
    {
        $service = $this->services->find(['name' => $this->mode]);

        if (empty($service)) {
            throw new \Exception("No service found for {$this->mode} !");
        }

        $result = [
            "name" => $this->getName(),
            "mode" => $this->getMode(),
            "date" => $this->getDate(),
            "generate" => $service->generate([
                "name" => $this->getName(),
                "date" => $this->getDate(),
                "teams" => $this->teams,
            ] + $config)
        ];

        return $hasArray ? SerializerEncoder::toArray($result) : $result;
    }

    /**
     * Add a service tournament generator to the collection
     */
    public function addService(ServiceInterface $service): Tournament
    {
        $this->services->add($service, $service->getName());

        return $this;
    }

    public function getServices(): ServicesCollection
    {
        return $this->services;
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

    public function getTeams(): TeamsCollection
    {
        return $this->teams;
    }

    /**
     * Create a default date if none pass
     * It's today at 20:00:00
     */
    public function setDate(CarbonImmutable $date = null, string $utc = 'UTC'): Tournament
    {
        if (!is_null($date)) {
            $this->date = $date;
        } else {
            $now = CarbonImmutable::now();
            $this->date = CarbonImmutable::create($now->format('Y'), $now->format('m'), $now->format('d'), 20, 0, 0, $utc);
        }

        return $this;
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