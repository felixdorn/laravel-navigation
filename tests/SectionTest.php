<?php

use Felix\Navigation\Item;
use Felix\Navigation\Navigation;
use Felix\Navigation\Section;

beforeEach(function () {
    $this->navigation = new Felix\Navigation\Navigation();
    $this->defaultExpectedTree = [
        [
            'name' => 'Section',
            'items' => [
                [
                    'name' => 'Hello',
                    'url' => null,
                    'active' => false,
                ],
            ],
        ]
    ];
});

it('can add a section', function () {
    $this->navigation->addSection('Section', fn(Section $section) => $section->add('Hello', fn() => null));

    expect($this->navigation->tree())->toBe($this->defaultExpectedTree);
});

it('can add a section unless a condition is false', function (bool|callable $condition) {
        $expectation = !value($condition) ? $this->defaultExpectedTree : [];

        $this->navigation->addSectionUnless($condition, 'Section', fn (Section $section) => $section->add('Hello', fn() => null));

        expect($this->navigation->tree())->toBe($expectation);
})->with([true, false, fn () => true, fn () => false]);

it('can add a section if a condition is true', function (bool|callable $condition) {
    $expectation = value($condition) ? $this->defaultExpectedTree : [];

    $this->navigation->addSectionIf($condition, 'Section', fn (Section $section) => $section->add('Hello', fn() => null));

    expect($this->navigation->tree())->toBe($expectation);
})->with([true, false, fn () => true, fn () => false]);

it('can be active', function () {
    $section = new Section('Section');
    expect($section->isActive())->toBeFalse();
    $section->add('Hello', fn(Item $item) => $item->href('/yes'));
    expect($section->isActive())->toBeFalse();
    $section->add('Hello', fn(Item $item) => $item->activePattern('/'));
    expect($section->isActive())->toBeTrue();
});
