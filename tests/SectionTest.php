<?php

use Felix\Navigation\Item;
use Felix\Navigation\Section;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->navigation          = new Felix\Navigation\Navigation();
    $this->defaultExpectedTree = [
        [
            'name'  => 'Section',
            'items' => [
                [
                    'name'   => 'Hello',
                    'url'    => null,
                    'active' => false,
                ],
            ],
        ],
    ];
});

it('can add a section', function () {
    $this->navigation->addSection('Section', fn (Section $section) => $section->add('Hello', fn () => null));

    expect($this->navigation->toArray())->toBe($this->defaultExpectedTree);
});

it('can add a section unless a condition is false', function (bool|callable $condition) {
    $expectation = !value($condition) ? $this->defaultExpectedTree : [];

    $this->navigation->addSectionUnless($condition, 'Section', fn (Section $section) => $section->add('Hello', fn () => null));

    expect($this->navigation->toArray())->toBe($expectation);
})->with([true, false, fn () => true, fn () => false]);

it('can add a section if a condition is true', function (bool|callable $condition) {
    $expectation = value($condition) ? $this->defaultExpectedTree : [];

    $this->navigation->addSectionIf($condition, 'Section', fn (Section $section) => $section->add('Hello', fn () => null));

    expect($this->navigation->toArray())->toBe($expectation);
})->with([true, false, fn () => true, fn () => false]);

it('is active if any of its children are active', function () {
    $section = new Section('');
    $section->add('A', fn (Item $item) => $item->url('/2'));
    $section->add('B', fn (Item $item) => $item->url('/1'));

    app()->bind('request', fn () => Request::create('/1'));
    expect($section->isActive())->toBeTrue();
    app()->bind('request', fn () => Request::create('/2'));
    expect($section->isActive())->toBeTrue();

    app()->bind('request', fn () => Request::create('/3'));
    expect($section->isActive())->toBeFalse();
});
