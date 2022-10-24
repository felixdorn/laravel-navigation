<?php

use Felix\Navigation\Item;

it('can set a name', function () {
    $item = new Item('Page');
    expect($item->name)->toBe('Page');
});

it('can set an active pattern', function () {
    $item = new Item('Page');
    expect($item->isActive())->toBeFalse();
    $item->activePattern('/');
    expect($item->isActive())->toBeTrue();
    $item->activePattern('/hello');
    expect($item->isActive())->toBeFalse();
});

it('can set some metadata', function () {
    $item = new Item('Page');
    expect($item->metadata)->toBe([]);
    $item->meta(['foo' => 'bar']);
    expect($item->metadata)->toBe(['foo' => 'bar']);
    $item->meta(['bar' => 'baz']);
    expect($item->metadata)->toBe(['foo' => 'bar', 'bar' => 'baz']);

});
