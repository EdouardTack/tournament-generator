<?php

use Tackacoder\Tournament\Components\Team;

it('initialize data Team', function () {
    $team = new Team([
        "name" => "Team Five",
        "status" => false
    ]);

    expect($team)->toHaveProperties(['uuid', 'name', 'status']);
    expect($team->getName())->toBe("Team Five");
    expect($team->getStatus())->toBeBool();
});

it('throw exception if name is not string', function () {
    new Team([
        "name" => [0]
    ]);
})->throws(Exception::class, 'Team name must be string data !');

it('throw exception if status is not boolean', function () {
    new Team([
        "name" => "Team Five",
        "status" => 1
    ]);
})->throws(Exception::class, 'Team status must be boolean data !');
