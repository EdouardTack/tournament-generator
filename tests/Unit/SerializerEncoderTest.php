<?php

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Components\Contest;
use Tackacoder\Tournament\Components\Schedule;
use Tackacoder\Tournament\Components\Team;
use Tackacoder\Tournament\Helpers\SerializerEncoder;

beforeEach(function () {
    $this->date = CarbonImmutable::now();
});

it('transform object into array', function () {
    $schedule = new Schedule($this->date);
    $schedule->addContest(new Contest(
        new Team([
            "uuid" => "pr6slor",
            "name" => "Team Home",
            "status" => false
        ]),
        new Team([
            "uuid" => "e5f4erg894",
            "name" => "Team Away",
            "status" => false
        ])
    ));

    expect(SerializerEncoder::toArray($schedule))
        ->toBe([
            "date" => $this->date->format(\DateTime::RFC3339),
            "contests" => [
                [
                    "homeTeam" => [
                        "uuid" => "pr6slor",
                        "name" => "Team Home",
                        "status" => false
                    ],
                    "awayTeam" => [
                        "uuid" => "e5f4erg894",
                        "name" => "Team Away",
                        "status" => false
                    ],
                    "homeScore" => 0,
                    "awayScore" => 0
                ]
            ]
        ]);
});