<?php

use Tackacoder\Tournament\Services\Service;

beforeEach(function () {
    $this->service = new class extends Service {
        protected string $name = 'test';
    };
});

test('Service object getters', function () {
    expect($this->service->getName())->toBe('test');
    expect($this->service->getName())->not->toBe('empty');
    expect($this->service->getCallable())->toBe(null);
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

it('has default config value', function () {
    $this->service->setConfig([
        'data' => 'string',
        'boolean' => true
    ]);

    expect($this->service->getConfig('unexptected', false))->toBeFalse();
});
