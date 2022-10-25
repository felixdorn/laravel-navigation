<?php

use Felix\Navigation\Item;
use Felix\Navigation\Navigation;
use Illuminate\Contracts\Support\Arrayable;

beforeEach(function () {
    $this->navigation = new Navigation();
});

it('is arrayable', function () {
    expect($this->navigation)->toBeInstanceOf(Arrayable::class);

    $this->navigation->add('1', fn() => true);

    expect($this->navigation->toArray())->toBe($this->navigation->tree());
});

it('can add items', function () {
    $this->navigation->add('Laravel', fn(Item $item) => $item->url('https://laravel.com'));

    expect($this->navigation->tree())->toBe([
        testItem('Laravel', 'https://laravel.com'),
    ]);
});

it('can add an item unless a condition is false', function (bool|callable $condition) {
    $expectation = !value($condition) ? [
        testItem(1),
        testItem(2),
    ] : [];

    $this->navigation->addUnless($condition, '1', fn () => null);
    $this->navigation->addUnless($condition, '2', fn () => null);

    expect($this->navigation->tree())->toBe($expectation);
})->with([true, false, fn() => true, fn() => false]);

it('can add an item if a condition is true', function (bool|callable $condition) {
    $expectation = value($condition) ? [
        testItem(1),
        testItem(2),
    ] : [];

    $this->navigation->addIf($condition, '1', fn () => null);
    $this->navigation->addIf($condition, '2', fn () => null);

    expect($this->navigation->tree())->toBe($expectation);
})->with([true, false, fn() => true, fn() => false]);

it('returns a tree when invoked', function () {
    $this->navigation
        ->add('One', fn() => null)
        ->add('Two', fn() => null)
        ->add('Three', fn() => null);

    expect(($this->navigation)())->toBe($this->navigation->tree());
});

it('does not need to return an item to configure it', function () {
    $this->navigation->add('Page', function (Item $item) {
        $item->url('//laravel.com');
    });

    expect($this->navigation->tree())->toBe([
        testItem('Page', '//laravel.com'),
    ]);
});

it('can iterate over the items', function () {
    $this->navigation
        ->add('1', fn() => null)
        ->add('2', fn() => null)
        ->add('3', fn() => null);

    expect($this->navigation)->toHaveCount(3);
    expect($this->navigation)->toBeIterable();

    $i = 1;
    foreach ($this->navigation as $item) {
        expect($item)->toBe(testItem($i));

        $i++;
    }
});

it('is macroable', function () {
    $navigation = Navigation::new('test', function (Navigation $navigation) {
        $navigation->add('Hey', fn() => null);
    });

    expect($navigation)->toBeInstanceOf(Navigation::class);
    expect($navigation)->toHaveCount(1);

    expect(Navigation::test()->tree())->toBe($navigation->tree());
});

it('throws an error if the macro does not exist', function () {
    Navigation::doesNotExist();
})->throws(BadMethodCallException::class);
