<?php

namespace Felix\Navigation;

use BadMethodCallException;
use Countable;
use Felix\Navigation\Concerns\WithNavigationTree;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;

/** @implements IteratorAggregate<int, Item|Section> */
class Navigation implements IteratorAggregate, Countable
{
    use WithNavigationTree;
    use Macroable;

    /** @var array<string, mixed> */
    protected array $injectedVariables = [];
    /** @var array<string, HtmlString> */
    protected array $slots = [];

    /** @param array{} $parameters  */
    public static function __callStatic(string $method, array $parameters): self
    {
        if (array_key_exists($method, static::$macros)) {
            $navigation = new static();

            call_user_func(static::$macros[$method], $navigation, ...$parameters);

            return $navigation;
        }

        throw new BadMethodCallException(sprintf('[%s] is not registered', $method));
    }

    /** @return array<string, mixed> */
    public function getInjectedVariables(): array
    {
        return $this->injectedVariables;
    }

    /** @return array<string, HtmlString>  */
    public function getSlots(): array
    {
        return $this->slots;
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

    public function inject(string $key, mixed $value): self
    {
        $this->injectedVariables[$key] = $value;

        return $this;
    }

    public function slot(string $name, string|callable|HtmlString $value): self
    {
        /* @phpstan-ignore-next-line */
        $this->slots[$name] = new HtmlString((string) value($value));

        return $this;
    }
}
