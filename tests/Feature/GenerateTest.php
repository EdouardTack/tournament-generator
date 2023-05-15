<?php

use Tackacoder\Tournament\Tournament;
use Tackacoder\Tournament\Components\Day;
use Tackacoder\Tournament\Services\ChampionshipService;

beforeEach(function () {
    $this->generateTournament = new Tournament('Titre', 'championship');
    $this->generateTournament->setTeams([
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
    ]);
});

it('throw Exception if no service with mode name found', function () {
    $this->generateTournament->setMode('nothing');
    $this->generateTournament->generate();
})->throws(Exception::class, "No service found for nothing !");

test('Generate a new championship Tournament', function () {
    $tournament = $this->generateTournament;
    $tournament->addService(new ChampionshipService());
    $result = $tournament->generate();

    expect($result)
        ->toBeArray()
        ->toMatchArray([
            'name' => 'Titre',
            'mode' => 'championship'
        ]);

    expect($result['generate'])
        ->toBeArray()
        ->toContainOnlyInstancesOf(Day::class);
});

test('Generate championship with closure function', function () {
    $tournament = $this->generateTournament;
    $tournament->addService(new ChampionshipService(callable: function ($args) {
        return null;
    }));
    $tournament->generate();
    expect($tournament->getServices()->find(['name' => 'championship'])->getCallable())->toBeCallable();
});
