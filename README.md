> Repository has been moved from [laravel-honda/navigation](https://github.com/laravel-honda/navigation)
> to [felixdorn/laravel-navigation](https://github.com/felixdorn/laravel-navigation).

# Navigation for Laravel

Create navigation menus for your Laravel application.

[![Tests](https://github.com/felixdorn/laravel-navigation/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/felixdorn/laravel-navigation/actions/workflows/tests.yml)
[![Formats](https://github.com/felixdorn/laravel-navigation/actions/workflows/formats.yml/badge.svg?branch=main)](https://github.com/felixdorn/laravel-navigation/actions/workflows/formats.yml)
[![Version](https://poser.pugx.org/felixdorn/laravel-navigation/version)](//packagist.org/packages/felixdorn/laravel-navigation)
[![Total Downloads](https://poser.pugx.org/felixdorn/laravel-navigation/downloads)](//packagist.org/packages/felixdorn/laravel-navigation)
[![codecov](https://codecov.io/gh/felixdorn/laravel-navigation/branch/main/graph/badge.svg?token=xEQb4DhPlr)](https://codecov.io/gh/felixdorn/laravel-navigation)

## Installation

> Requires [PHP 8.0+](https://php.net/releases)

You can install the package via composer:

```bash
composer require felixdorn/laravel-navigation
```

## Usage

### Creating a navigation bar

```php
use Felix\Navigation\Navigation;

Navigation::macro('theName', function (Navigation $navigation) {
    // ...
});
```

### Rendering a navigation bar

```php
use Felix\Navigation\Navigation;

Navigation::theName();
```

### Items

#### Href

If you pass a route name like `login` or `articles.index`, the actual path will be resolved. You may pass additional
context to the route resolver.

If you pass anything else, it will be rendered as-is.

```php
$item->href('articles.index');
```

```php
$item->href('articles.edit', ['article' => 1]);
```

```php
$item->href('https://repo.new');
```

#### Icon

This package integrates seamlessly with [Blade Icons](https://github.com).

```php
$item->icon('heroicon-eye');
```

#### Force active state

This will bypass a potentially defined active pattern and force the item to be rendered as an active one.

```php
$item->alwaysActive();
```

#### Active pattern

Mark an item as active based on an advanced pattern. The resolved route path is used if no active pattern is provided.
Check out [URL Pattern Matcher](https://github.com/laravel-honda/url-pattern-matcher) for more details.

```php
$item->activePattern('/articles/*');
```

#### Conditionally rendered items

```php
use Felix\Navigation\Item;

$navigation->addIf($isAdmin, 'Settings', function (Item $item) {
    // ...
});
$navigation->addUnless($isReader, 'Articles', function (Item $item) {
    // ...
});
```

### Section

#### Add a section

```php
use Felix\Navigation\Item;
use Felix\Navigation\Section;

$navigation->addSection('Name', function (Section $section) {
    $section->add('Child', function (Item $item) {
        // ...
    });
});
```

#### Conditionally rendered sections

```php
use Felix\Navigation\Section;

$navigation->addSectionIf($isAdmin, 'Admin', function (Section $section) {
    // ...
});
$navigation->addSectionUnless($isReader, 'Bookmarks', function (Section $section) {
    // ...
});
```

## Testing

```bash
composer test
```

**Navigation for Laravel** was created by **[Félix Dorn](https://twitter.com/afelixdorn)** under
the **[MIT license](https://opensource.org/licenses/MIT)**.
