<?php

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Tournament;

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
