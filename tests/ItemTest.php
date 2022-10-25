<?php

use Felix\Navigation\Item;
use Illuminate\Support\Facades\Route;

it('can set a name', function () {
    $item = new Item('Page');
    expect($item->name)->toBe('Page');
});

it('can match routes with the exact same name', function () {
    $r = Route::get('/not-named-hello')->name('hello');

    $item = (new Item(''))->route('hello');

    expect($item->isActive())->toBeFalse();

    request()->setRouteResolver(fn () => $r);

    expect($item->isActive())->toBeTrue();
});

it('can match routes using a pattern', function (string $route, string $currentRoute, bool $isActive) {
    $r = Route::get($path = time())->name($route);

    request()->setRouteResolver(fn () => $r);

    $item = (new Item(''))
        ->route($route)
        ->activeWhenRouteMatches($currentRoute);

    expect($item->url)->toBe('http://localhost/' . $path);
    expect($item->isActive())->toBe($isActive);
})->with([
    ['articles', 'articles*', true],
    ['articles.', 'articles.*', true],
    ['not-articles', 'articles.*', false],
    ['articles-but-no', 'articles.*', false],
    ['articles.edit', 'articles.*', true],
    ['articles.tags.edit', 'articles.*.edit', true],
    ['articles.tags.not-edit', 'articles.*.edit', false],
]);

it('can match using a url', function (string $url, string $requestUrl, bool $isActive) {
    $item = mock(Item::class)->makePartial()
        ->shouldAllowMockingProtectedMethods()
        ->expects('getCurrentPath')->andReturn($requestUrl)->once()->withNoArgs()
        ->getMock()
        ->url($url);

    expect($item->isActive())->toBe($isActive);
})->with([
    ['/url', '/url', true],
    ['/url/', '/url', true],
    ['/url', '/not-url', false],
]);

it('can change the activeness resolver', function () {
    $item = (new Item('Page'))->url('https://google.com');

    expect($item->isActive())->toBeFalse();

    Item::setActivenessResolverCallback($callback = fn (Item $item) => true);

    expect($callback)->toBe(Item::getActivenessResolverCallback());
    expect($item->isActive())->toBeTrue();

    // idempotence matters a lot here.
    Item::setActivenessResolverCallback([Item::class, 'defaultActivenessResolverCallback']);
});

it('can set metadata', function () {
    $item = new Item('Page');
    $item->meta(['foo' => 'bar']);
    expect($item->toArray())->toHaveKey('foo', 'bar');
    $item->bar('baz');
    expect($item->toArray())
        ->toHaveKey('foo', 'bar')
        ->toHaveKey('bar', 'baz');
});

it('throws an error when adding metadata without a value', function () {
    $item = new Item('');

    $item->foo();
})->throws('Missing value for metadata key foo. Usage: $item->foo(\'value\')');
