<?php

namespace Felix\Navigation;

use Felix\UrlResolver\UrlResolver;
use Honda\UrlPatternMatcher\UrlPatternMatcher;
use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    public string $name;
    public ?string $url = null;
    public ?string $pattern = null;
    public array $metadata = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function href(string $href, mixed $context = []): self
    {
        $this->url = UrlResolver::guess($href, $context);

        return $this;
    }

    public function meta(array $metadata): self
    {
        $this->metadata = [...$this->metadata, ...$metadata];

        return $this;
    }

    public function activePattern(string $pattern): self
    {
        $this->pattern = $pattern;

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
        if (empty($this->pattern) && empty($this->url)) {
            return false;
        }

        $matcher = new UrlPatternMatcher($this->pattern ?? $this->url ?? '');

        /* @phpstan-ignore-next-line */
        return $this->pattern !== null && $matcher->match(request()->path());
    }
}
