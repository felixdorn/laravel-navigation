<?php

namespace Felix\Navigation;

use BadMethodCallException;
use Countable;
use Felix\Navigation\Concerns\WithNavigationTree;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;

/** @implements IteratorAggregate<int, Item|Section> */
class Navigation implements IteratorAggregate, Countable, Arrayable
{
    use WithNavigationTree;

    protected static array $navigations = [];

    /**
     * @param string $name
     * @param callable{Navigation} $callback
     * @return static
     */
    public static function new(string $name, callable $callback): static
    {
        $navigation = new static();
        $callback($navigation);

        static::$navigations[$name] = $navigation;

        return $navigation;
    }

    public static function __callStatic(string $name, array $arguments): static
    {
        if (isset(static::$navigations[$name])) {
            return static::$navigations[$name];
        }

        throw new BadMethodCallException("Navigation {$name} does not exist. Did you register it using Navigation::new() ?");
    }

    public function addSectionIf(bool|callable $condition, string $name, callable $builder): self
    {
        if (value($condition)) {
            $this->addSection($name, $builder);
        }

        return $this;
    }

    public function addSection(string $name, callable $builder): self
    {
        $this->tree[] = [new Section($name), $builder];

        return $this;
    }

    public function addSectionUnless(bool|callable $condition, string $name, callable $builder): self
    {
        if (!value($condition)) {
            $this->addSection($name, $builder);
        }

        return $this;
    }

    public function toArray(): array
    {
        return $this->tree();
    }
}
