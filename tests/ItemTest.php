<?php

use Felix\Navigation\Item;

it('can set a name', function () {
    $item = new Item('Page');
    expect($item->name)->toBe('Page');
});

it('can set an icon', function () {
    $item = new Item('Page');
    expect($item->icon)->toBeNull();
    $item->icon('circle-check');
    expect($item->icon)->toBe('circle-check');
});

it('can set an active pattern', function () {
    $item = new Item('Page');
//    expect($item->isActive())->toBeFalse();
    $item->activePattern('/');
    expect($item->isActive())->toBeTrue();
    $item->activePattern('/hello');
    expect($item->isActive())->toBeFalse();
});

it('can be always active', function () {
    $item = new Item('Page');
    expect($item->isActive())->toBeFalse();
    $item->alwaysActive();
    expect($item->isActive())->toBeTrue();
});
