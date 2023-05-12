<?php

use Tackacoder\Tournament\Tournament;
use Tackacoder\Tournament\Services\ChampionshipService;
use Tackacoder\Tournament\Services\CupService;

beforeEach(function () {
    $this->tournament = new Tournament('Titre', 'championship');
    $this->tournament->setTeams([
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
    $this->tournament->setMode('nothing');
    $this->tournament->generate();
})->throws(Exception::class, "No service found for nothing !");

test('Generate a new championship Tournament', function () {
    $this->tournament->addService(new ChampionshipService());
    $data = $this->tournament->generate();

    // dump($data);
    expect($data)
        ->toBeArray();
});
