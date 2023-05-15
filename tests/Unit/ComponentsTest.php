<?php

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Components\Contest;
use Tackacoder\Tournament\Components\Day;
use Tackacoder\Tournament\Components\Schedule;
use Tackacoder\Tournament\Components\Team;

beforeEach(function () {
    $this->day = new Day("First day");
    $this->schedule = new Schedule(CarbonImmutable::now());
    $this->contest = new Contest(
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
    );

    $this->schedule->addContest($this->contest);
    $this->day->addSchedule($this->schedule);
});

it('initialize Contest object', function () {
    expect($this->contest)->toBeInstanceOf(Contest::class);
    expect($this->contest)->toHaveProperties(['home', 'away']);
    expect($this->contest->getHomeTeam())->toBeInstanceOf(Team::class);
    expect($this->contest->getAwayTeam())->toBeInstanceOf(Team::class);
    expect($this->contest->getHomeScore())->toBe(0);
    expect($this->contest->getAwayScore())->toBe(0);
});

it('initialize Day object', function () {
    expect($this->day)->toBeInstanceOf(Day::class);
    expect($this->day->getName())->toBe("First day");
    expect($this->day->getSchedules())->toContainOnlyInstancesOf(Schedule::class);
});

it('initialize Schedule object', function () {
    expect($this->schedule)->toBeInstanceOf(Schedule::class);
    expect($this->schedule->getDate())->toBeInstanceOf(CarbonImmutable::class);
    expect($this->schedule->getContests())->toContainOnlyInstancesOf(Contest::class);
});