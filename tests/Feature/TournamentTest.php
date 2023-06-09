<?php

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Tournament;
use Tackacoder\Tournament\Collections\ServicesCollection;
use Tackacoder\Tournament\Collections\TeamsCollection;
use Tackacoder\Tournament\Components\Team;
use Tackacoder\Tournament\Services\ChampionshipService;
use Tackacoder\Tournament\Services\CupService;
use Tackacoder\Tournament\Supports\ServiceInterface;

beforeEach(function () {
    $this->tournament = new Tournament('Titre', 'championship');
});

it('initialize a new tournament "championship"', function () {
    expect($this->tournament->getName())->toBe('Titre');
    expect($this->tournament->getMode())->toBe('championship');
    expect($this->tournament->getDate())->toBeInstanceOf(CarbonImmutable::class);
});

it('can not change construct date', function () {
    $tomorrow = $this->tournament->getDate()->addDay();

    expect($tomorrow->toDateTimeString())
        ->not->toBe($this->tournament->getDate()->toDateTimeString());
});

it('can change name and mode after initialization', function () {
    expect($this->tournament->getName())->toBe('Titre');
    expect($this->tournament->getMode())->toBe('championship');

    $this->tournament
        ->setName('Edit')
        ->setMode('bracket');

    expect($this->tournament->getName())->toBe('Edit');
    expect($this->tournament->getMode())->toBe('bracket');
});

it('can add generator services', function () {
    $this->tournament->addService(new ChampionshipService());
    $this->tournament->addService(new ChampionshipService());
    $this->tournament->addService(new CupService());

    expect($this->tournament->getServices())
        ->toBeInstanceOf(ServicesCollection::class);
       
    expect($this->tournament->getServices()->toArray())
        ->toBeArray()
        ->toBeIterable()
        ->toContainOnlyInstancesOf(ServiceInterface::class)
        ->toHaveCount(2);
});

it('can define or add some teams', function () {
    $teams = [
        [
            "name" => "Team One",
            "status" => true
        ],
        [
            "name" => "Team Two",
            "status" => false
        ],
        [
            "name" => "Team Three",
            "status" => true
        ],
        [
            "name" => "Team Four",
            "status" => true
        ]
    ];

    $this->tournament->setTeams($teams);

    expect($this->tournament->getTeams())
        ->toBeInstanceOf(TeamsCollection::class);

    expect($this->tournament->getTeams()->toArray())
        ->toBeArray()
        ->toContainOnlyInstancesOf(Team::class)
        ->toHaveCount(4);

    $this->tournament->addTeam([
        "name" => "Team Five",
        "status" => false
    ]);

    expect($this->tournament->getTeams()->toArray())
        ->toBeIterable();
    
    expect($this->tournament->getTeams()->toArray())
        ->toHaveCount(5);
});

it ('can set date', function () {
    $this->tournament->setDate(utc: 'Europe/Paris');
    expect($this->tournament->getDate())
        ->toBeInstanceOf(CarbonImmutable::class);
    expect($this->tournament->getDate()->toDateTimeString())->toBe(date('Y-m-d 20:00:00'));

    $this->tournament->setDate(CarbonImmutable::now());
    expect($this->tournament->getDate())
        ->toBeInstanceOf(CarbonImmutable::class);
    expect($this->tournament->getDate()->toDateTimeString())->toBe(date('Y-m-d H:i:s'));
});