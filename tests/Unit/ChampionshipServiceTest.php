<?php

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Tackacoder\Tournament\Collections\ContestsCollection;
use Tackacoder\Tournament\Collections\TeamsCollection;
use Tackacoder\Tournament\Components\Contest;
use Tackacoder\Tournament\Components\Day;
use Tackacoder\Tournament\Services\ChampionshipService;

beforeEach(function () {
    $this->date = CarbonImmutable::now();
    $this->teamsCollection = new TeamsCollection();
    for ($i = 1;$i <= 8;$i++) {
        $this->teamsCollection->add([
            "id" => $i,
            "name" => "Team $i"
        ]);
    }

    $this->myChampionShipService = new ChampionshipService();
});

test('overload config', function () {
    $this->myChampionShipService->generate([
        "name" => 'My championship with one matche by team',
        "date" => $this->date,
        "teams" => $this->teamsCollection,
        "shift" => 1
    ]);

    expect($this->myChampionShipService->getConfig('shift'))
        ->toBe(1);
    expect($this->myChampionShipService->getConfig('mirror'))
        ->toBeFalse();
});

it('can generate only one match for each team', function () {
    $this->myChampionShipService->setConfig([
        "name" => 'My championship with one matche by team',
        "date" => $this->date,
        "teams" => $this->teamsCollection,
        "mirror" => false
    ]);

    expect($this->myChampionShipService->getConfig('shift'))
        ->toBe(null);

    $rounds = $this->myChampionShipService->rounds();
    
    expect($rounds)
        ->toBeArray()
        ->toHaveCount(7);

    foreach ($rounds as $round) {
        expect($round)
            ->toBeArray()
            ->toContainOnlyInstancesOf(Contest::class)
            ->toHaveCount(4);
    }
});

it('generate mirrors matches with shift of 4', function () {
    $this->myChampionShipService->setConfig([
        "name" => 'My championship with home/away matches',
        "date" => $this->date,
        "teams" => $this->teamsCollection,
        "mirror" => true,
        "shift" => 4
    ]);
    $rounds = $this->myChampionShipService->rounds();

    expect($rounds)
        ->toBeArray()
        ->toHaveCount(14);

    /**
     * First match inverse with 11th match
     */
    expect([
        $rounds[0][0]->getHomeTeam()->getUuid(),
        $rounds[0][0]->getAwayTeam()->getUuid()
    ])->toBe([
        $rounds[10][0]->getAwayTeam()->getUuid(),
        $rounds[10][0]->getHomeTeam()->getUuid()
    ]);

    /**
     * Third match inverse with last match
     */
    expect([
        $rounds[3][0]->getHomeTeam()->getUuid(),
        $rounds[3][0]->getAwayTeam()->getUuid()
    ])->toBe([
        $rounds[13][0]->getAwayTeam()->getUuid(),
        $rounds[13][0]->getHomeTeam()->getUuid()
    ]);

    /**
     * 5th match inverse with 9th match
     */
    expect([
        $rounds[5][0]->getHomeTeam()->getUuid(),
        $rounds[5][0]->getAwayTeam()->getUuid()
    ])->toBe([
        $rounds[8][0]->getAwayTeam()->getUuid(),
        $rounds[8][0]->getHomeTeam()->getUuid()
    ]);
});

test('gap for one team between home and away', function () {
    $this->myChampionShipService->setConfig([
        "name" => 'My championship with home/away matches',
        "date" => $this->date,
        "teams" => $this->teamsCollection,
        "mirror" => true,
        "shuffle" => false,
        "shift" => 4
    ]);
    $rounds = $this->myChampionShipService->rounds();
    
    $gap = [];
    foreach ($rounds as $contests) {
        foreach($contests as $contest) {
            if ($contest->getHomeTeam()->getId() == 1) {
                $gap[] = "1";
            }

            if ($contest->getAwayTeam()->getId() == 1) {
                $gap[] = "0";
            }
        }
    }

    expect(implode('', $gap) == "11010101010010")
        ->toBeTrue();
});

test('object Day after rounds creation', function () {
    $this->myChampionShipService->setConfig([
        "name" => 'Test Day object',
        "date" => $this->date,
        "teams" => $this->teamsCollection,
        "mirror" => false
    ]);
    $rounds = $this->myChampionShipService->rounds();

    $date = $this->myChampionShipService->matches($rounds[0], $this->date->add(CarbonInterval::make('1 Day')), 1);
    expect($date)
        ->toBeInstanceOf(CarbonImmutable::class);

    expect($this->myChampionShipService->getContests())
        ->toBeInstanceOf(ContestsCollection::class);
    expect($this->myChampionShipService->getContests()->toArray())
        ->toContainOnlyInstancesOf(Day::class);
});
