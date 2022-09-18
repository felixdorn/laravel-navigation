<?php

use Pest\Expectation;

uses(\Orchestra\Testbench\TestCase::class)->in('.');

expect()->extend('toMatchObjectDeeply', function ($object) {
    if (is_object($object)) {
        /* @var Expectation $this */
        $this->toBeInstanceOf(get_class($object));

        $reflection = new ReflectionObject($this->value);
        $comparison = new ReflectionObject($object);

        $reflectionProperties = $reflection->getProperties();

        foreach ($reflectionProperties as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);

            expect($comparison->hasProperty($reflectionProperty->getName()))->toBeTrue();

            $comparedProperty = $comparison->getProperty($reflectionProperty->getName());
            $comparedProperty->setAccessible(true);

            if ($reflectionProperty->isInitialized($this->value) && !$comparedProperty->isInitialized($object)) {
                expect(false)->toBe("property $reflectionProperty->name should be initialized");
            }

            if ($reflectionProperty->isInitialized($this->value)) {
                $value = $reflectionProperty->getValue($this->value);

                $compared = $comparedProperty->getValue($object);
                if (is_object($value)) {
                    return expect($value)->toMatchObjectDeeply($compared);
                }

                if (is_array($value)) {
                    return expect($value)->toMatchTree($compared);
                }

                expect($value)->toBe($compared);
            }
        }

        return;
    }

    if (is_array($object)) {
        return expect($this->value)->toMatchTree($object);
    }

    expect($this->value)->toBe($object);
});

expect()->extend('toMatchTree', function (iterable $iterable) {
    /* @var Expectation $this */
    foreach ($iterable as $k => $value) {
        $this->toHaveKey($k);
        expect($this->value[$k])->toMatchObjectDeeply($iterable[$k]);
    }

    return $this;
});
