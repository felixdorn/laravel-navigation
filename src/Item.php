<?php

namespace Felix\Navigation;

use Felix\UrlResolver\UrlResolver;
use Honda\UrlPatternMatcher\UrlPatternMatcher;

class Item
{
    public string $name;
    public ?string $href      = null;
    public ?string $icon      = null;
    public ?string $pattern   = null;
    public bool $alwaysActive = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function href(string $href, mixed $context = []): self
    {
        $this->href = UrlResolver::guess($href, $context);

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function alwaysActive(): self
    {
        $this->alwaysActive = true;

        return $this;
    }

    public function isActive(): bool
    {
        if ($this->alwaysActive) {
            return true;
        }

        if (empty($this->pattern) && empty($this->href)) {
            return false;
        }

        $matcher = new UrlPatternMatcher($this->pattern ?? $this->href ?? '');

        /* @phpstan-ignore-next-line */
        return $this->pattern !== null && $matcher->match(request()->path());
    }

    public function activePattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }
}
