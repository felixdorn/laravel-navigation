<?php

namespace Felix\Navigation;

use BadMethodCallException;
use Countable;
use Felix\Navigation\Concerns\WithNavigationTree;
use IteratorAggregate;

/** @implements IteratorAggregate<int, Item|Section> */
class Navigation implements IteratorAggregate, Countable
{
    use WithNavigationTree;

    /** @var static[] */
    protected static array $navigations = [];

    /** @param callable(self):mixed $callback */
    public static function register(string $name, callable $callback): static
    {
        $navigation = new static();
        $callback($navigation);

        static::$navigations[$name] = $navigation;

        return $navigation;
    }

    /** @param array{} $arguments */
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

    /** @return (non-empty-array<scalar|null>|array{name: string, items: array<int, array<string, mixed>>})[] */
    public function toArray(): array
    {
        return array_map(fn (Item|Section $e) => $e->toArray(), $this->tree());
    }
}
