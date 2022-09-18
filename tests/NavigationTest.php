<?php

use Felix\Navigation\Item;
use Felix\Navigation\Navigation;
use Illuminate\Support\Traits\Macroable;

it('can add items', function () {
    $navigation = new Navigation();
    $navigation
        ->add('Laravel', fn (Item $item) => $item->href('https://laravel.com'));

    expect($navigation->tree())->toMatchTree([
        (new Item('Laravel'))->href('https://laravel.com'),
    ]);
});
it('can add items if a condition is true', function () {
    expect(
        (new Navigation())
            ->addIf(true, 'Home', fn () => null)
            ->tree()
    )->toMatchTree([new Item('Home')]);

    expect(
        (new Navigation())
            ->addIf(fn () => true, 'Home', fn () => null)
            ->tree()
    )->toMatchTree([new Item('Home')]);

    expect(
        (new Navigation())
            ->addIf(false, 'Home', fn () => null)
            ->tree()
    )->toBeEmpty();

    expect(
        (new Navigation())
            ->addIf(fn () => false, 'Home', fn () => null)
            ->tree()
    )->toBeEmpty();
});
it('can add items if unless a condition is true', function () {
    expect(
        (new Navigation())
            ->addUnless(true, 'Home', fn () => null)
            ->tree()
    )->toBeEmpty();

    expect(
        (new Navigation())
            ->addUnless(fn () => true, 'Home', fn () => null)
            ->tree()
    )->toBeEmpty();

    expect(
        (new Navigation())
            ->addUnless(false, 'Home', fn () => null)
            ->tree()
    )->toMatchTree([new Item('Home')]);

    expect(
        (new Navigation())
            ->addUnless(fn () => false, 'Home', fn () => null)
            ->tree()
    )->toMatchTree([new Item('Home')]);
});

it('returns a tree when invoked', function () {
    $navigation = (new Navigation())
        ->add('One', fn () => null)
        ->add('Two', fn () => null)
        ->add('Three', fn () => null);

    expect($navigation())->toMatchTree([
        new Item('One'),
        new Item('Two'),
        new Item('Three'),
    ]);
});

it('calls the method tree when invoked', function () {
    $navigation = new Navigation();

    $navigation->add('One', fn () => null);

    expect($navigation())->toMatchTree([
        new Item('One'),
    ]);
});

it('does not need to return an item to configure it', function () {
    $navigation = (new Navigation())
        ->add('Page', function (Item $item) {
            $item->href('//laravel.com');
        })->tree();

    expect($navigation)->toMatchTree([
        (new Item('Page'))->href('//laravel.com'),
    ]);
});

it('can iterate over the items', function () {
    $navigation = (new Navigation())->add('Page', fn () => null);

    foreach ($navigation as $item) {
        expect($item)->toMatchObjectDeeply(new Item('Page'));
    }
});

it('can register slots', function () {
    $navigation = new Felix\Navigation\Navigation();

    $navigation->slot('a', fn () => 'b');
    $navigation->slot('b', 'c');

    $slots = $navigation->getSlots();

    expect($slots['a']->toHtml())->toBe('b');
    expect($slots['b']->toHtml())->toBe('c');
});
it('can register injected variables', function () {
    $navigation = new Felix\Navigation\Navigation();

    $navigation->inject('a', [4, 5]);
    $navigation->inject('b', 'c');

    expect($navigation->getInjectedVariables())->toBe([
        'a' => [4, 5],
        'b' => 'c',
    ]);
});

it('is macroable', function () {
    expect(class_uses(Navigation::class))->toContain(Macroable::class);

    Navigation::macro('test', function (Navigation $navigation) {
        $navigation->add('Hey', fn () => null);
    });

    expect(Navigation::test())->toBeInstanceOf(Navigation::class);
    expect(Navigation::test()->tree())->toMatchTree([
        new Item('Hey'),
    ]);
});

it('throws an error if the macro does not exist', function () {
    Navigation::doesNotExist();
})->throws(BadMethodCallException::class);
