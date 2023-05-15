<?php

use Tackacoder\Tournament\Services\Service;

beforeEach(function () {
    $this->service = new class extends Service {};
});

it('initialize without config values', function () {
    expect($this->service->getConfig('data'))->toBe(null);
    expect($this->service->getConfig('boolean'))->toBe(null);
    expect($this->service->getConfig('unexptected'))->toBe(null);
});

it('set config values', function () {
    $this->service->setConfig([
        'data' => 'string',
        'boolean' => true
    ]);

    expect($this->service->getConfig('data'))->toBe('string');
    expect($this->service->getConfig('boolean'))->toBeTrue();
    expect($this->service->getConfig('unexptected'))->toBe(null);
});
