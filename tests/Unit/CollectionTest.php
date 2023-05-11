<?php

use Tackacoder\Tournament\Collections\ServicesCollection;

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
    
    $collection->define(['element-3']);

    expect($collection->toArray())
        ->toBeArray()
        ->toHaveCount(1);
});