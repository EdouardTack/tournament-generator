<?php

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Tournament;
use Tackacoder\Tournament\Services\ChampionshipService;
use Tackacoder\Tournament\Services\CupService;
use Tackacoder\Tournament\Services\ServicesCollection;
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

it('can add generator services', function () {
    $this->tournament->addService(new ChampionshipService());
    $this->tournament->addService(new ChampionshipService());
    $this->tournament->addService(new CupService());

    expect($this->tournament->getServices())
        ->toBeArray()
        ->toContainOnlyInstancesOf(ServiceInterface::class)
        ->toHaveCount(2);
});

it('can use services collection as array', function () {
    $collection = new ServicesCollection();
    $collection[] = 'element';
    $collection[] = 'element-2';

    expect($collection->toArray())
        ->toBeArray()
        ->toHaveCount(2);
    
    expect($collection[0])->toBe('element');
    expect($collection[1])->toBe('element-2');
    expect(isset($collection[1]))->toBeTrue();
    expect(isset($collection[2]))->toBeFalse();

    unset($collection[0]);
    expect($collection->toArray())
        ->toHaveCount(1);
});