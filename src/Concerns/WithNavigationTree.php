<?php

namespace Felix\Navigation\Concerns;

use ArrayIterator;
use Felix\Navigation\Item;
use Felix\Navigation\Section;

trait WithNavigationTree
{
    /** @var array<int, array{Item|Section, callable}> */
    protected array $tree = [];

    /** @return array<int, Item|Section> */
    public function __invoke(): array
    {
        return $this->tree();
    }

    /** @return array<int, Item|Section> */
    public function tree(): array
    {
        $built = [];

        foreach ($this->tree as $branch) {
            [$element, $builder] = $branch;

            $builtElement = $builder($element);
            if (!$builtElement) {
                $builtElement = $element;
            }

            $built[] = $builtElement;
        }

        return $built;
    }

    public function addIf(callable|bool $condition, string $name, callable $builder): self
    {
        if (value($condition)) {
            $this->add($name, $builder);
        }

        return $this;
    }

    public function add(string $name, callable $builder): self
    {
        $this->tree[] = [new Item($name), $builder];

        return $this;
    }

    public function addUnless(callable|bool $condition, string $name, callable $builder): self
    {
        if (!value($condition)) {
            $this->add($name, $builder);
        }

        return $this;
    }

    /** @return ArrayIterator<int, Item|Section> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->tree());
    }

    public function count(): int
    {
        return count($this->tree());
    }
}
