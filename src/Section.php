<?php

namespace Felix\Navigation;

use Felix\Navigation\Concerns\WithNavigationTree;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

/** @implements IteratorAggregate<int, Item|Section> */
class Section implements IteratorAggregate, Arrayable
{
    use WithNavigationTree;

    public function __construct(public readonly string $name)
    {
    }

    public function isActive(): bool
    {
        foreach ($this->tree() as $item) {
            if ($item['active']) {
                return true;
            }
        }

        return false;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'items' => $this->tree(),
        ];
    }
}
