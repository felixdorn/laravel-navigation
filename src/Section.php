<?php

namespace Felix\Navigation;

use Felix\Navigation\Concerns\WithNavigationTree;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, Item|Section>
 */
class Section implements IteratorAggregate
{
    use WithNavigationTree;

    public function __construct(public readonly string $name)
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

    /** @return array{name: string, items: array<int, array<string, mixed>>} */
    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'items' => array_map(fn (Item|Section $e) => $e->toArray(), $this->tree()),
        ];
    }
}
