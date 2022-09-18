<?php

use Felix\Navigation\Item;
use Felix\Navigation\Navigation;
use Felix\Navigation\Section;

it('can add a section', function () {
    $navigation = new Navigation();

    $navigation->addSection('Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toMatchTree([
        $builder(new Section('Section')),
    ]);
});

it('can add a section if a condition is true', function () {
    $navigation = new Navigation();

    $navigation->addSectionIf(true, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toMatchTree([
        $builder(new Section('Section')),
    ]);
});

it('can add a section if a callable returns true', function () {
    $navigation = new Navigation();

    $navigation->addSectionIf(fn () => true, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toMatchTree([
        $builder(new Section('Section')),
    ]);
});

it('can add a section unless a condition is true', function () {
    $navigation = new Navigation();

    $navigation->addSectionUnless(fn () => false, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toMatchTree([
        $builder(new Section('Section')),
    ]);
});

it('can add a section unless a callable returns true', function () {
    $navigation = new Navigation();

    $navigation->addSectionUnless(fn () => false, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toMatchTree([
        $builder(new Section('Section')),
    ]);
});

it('does not add a section if a condition is false', function () {
    $navigation = new Navigation();

    $navigation->addSectionIf(false, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toBeEmpty();
});

it('does not add a section if a callable returns false', function () {
    $navigation = new Navigation();

    $navigation->addSectionIf(fn () => false, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toBeEmpty();
});

it('does not add a section unless a condition is false', function () {
    $navigation = new Navigation();

    $navigation->addSectionUnless(fn () => true, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toBeEmpty();
});

it('does not add a section unless a callable returns false', function () {
    $navigation = new Navigation();

    $navigation->addSectionUnless(fn () => true, 'Section', $builder = function (Section $section) {
        return $section->add('Hello', fn () => null);
    });

    expect($navigation->tree())->toBeEmpty();
});

it('can be active', function () {
    $section = new Section('Section');
    expect($section->isActive())->toBeFalse();
    $section->add('Hello', fn (Item $item) => $item->href('/yes'));
    expect($section->isActive())->toBeFalse();
    $section->add('Hello', fn (Item $item) => $item->activePattern('/'));
    expect($section->isActive())->toBeTrue();
});
