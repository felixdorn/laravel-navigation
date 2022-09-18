<?php

namespace Felix\Navigation;

use Felix\Navigation\Concerns\WithNavigationTree;
use IteratorAggregate;

/** @implements IteratorAggregate<int, Item|Section> */
class Section implements IteratorAggregate
{
    use WithNavigationTree;

    public function __construct(public string $name)
    {
    }

    public function isActive(): bool
    {
        foreach ($this->tree() as $item) {
            if ($item->isActive()) {
                return true;
            }
        }

        return false;
    }
}
