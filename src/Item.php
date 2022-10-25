<?php

namespace Felix\Navigation;

use BadMethodCallException;
use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    /** @var callable(self): bool Returns true if the current item should be marked as active, false otherwise. */
    protected static $activenessResolverCallback = [self::class, 'defaultActivenessResolverCallback'];

    public ?string $route = null;
    public ?string $url = null;
    /** @var array<string, scalar> */
    public array $metadata = [];

    public function __construct(
        public string $name
    )
    {
    }

    public static function getActivenessResolverCallback(): callable
    {
        return self::$activenessResolverCallback;
    }

    /** @param callable(self): bool $callback */
    public static function setActivenessResolverCallback(callable $callback): void
    {
        self::$activenessResolverCallback = $callback;
    }

    protected static function defaultActivenessResolverCallback(Item $item): bool
    {
        if ($item->route === null && $item->url === null) {
            return false;
        }

        if ($item->route === null) {
            $comparison = parse_url($item->url, PHP_URL_PATH);

            if (!is_string($comparison)) {
                return false;
            }

            $comparison = rtrim($comparison, '/') ?: '';

            return $item->getCurrentPath() === $comparison;
        }

        return request()->routeIs($item->route);
    }

    protected function getCurrentPath(): string
    {
        return '/' . ltrim(request()->path(), '/');
    }

    public function route(string $route, array $context = []): self
    {
        $this->route = $route;
        $this->url = route($route, $context);

        return $this;
    }

    public function activeWhenRouteMatches(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    /** @param array<string, scalar> $metadata */
    public function meta(array $metadata): self
    {
        $this->metadata = [...$this->metadata, ...$metadata];

        return $this;
    }

    /**
     * @param array{mixed} $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        if (count($arguments) !== 1) {
            throw new BadMethodCallException("Missing value for metadata key {$name}. Usage: \$item->{$name}('value')");
        }

        $this->metadata[$name] = $arguments[0];

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'active' => $this->isActive(),
            ...$this->metadata,
        ];
    }

    public function isActive(): bool
    {
        return call_user_func(static::$activenessResolverCallback, $this);
    }

    public function url(string $url, array $parameters = [], ?bool $secure = null): self
    {
        $this->url = url($url, $parameters, $secure);

        return $this;
    }
}
