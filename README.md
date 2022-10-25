# Navigation for Laravel

Create navigation menus for your Laravel application, works out of the box with Inertia.

## Features

* Inertia support
* Conditionally add sections / items.
* Easily specify if a route is active
* Add metadata to items

[![Tests](https://github.com/felixdorn/laravel-navigation/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/felixdorn/laravel-navigation/actions/workflows/tests.yml)
[![Formats](https://github.com/felixdorn/laravel-navigation/actions/workflows/formats.yml/badge.svg?branch=main)](https://github.com/felixdorn/laravel-navigation/actions/workflows/formats.yml)
[![Version](https://poser.pugx.org/felixdorn/laravel-navigation/version)](//packagist.org/packages/felixdorn/laravel-navigation)
[![Total Downloads](https://poser.pugx.org/honda/navigation/downloads)](//packagist.org/packages/felixdorn/laravel-navigation)
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

Navigation::register('dashboardSidebar', function (Navigation $navigation) {
    
});
```

### Retrieving a navigation bar

```php
use Felix\Navigation\Navigation;

Navigation::dashboardSidebar()->toArray();
// alternatively, to get the raw tree underneath:
Navigation::dashboardSidebar()->tree();
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

### Items

```php
/** @var \Felix\Navigation\Item $item **/
$item->route('articles.index');

$item->route('tenant.show', ['tenant' => 1]);

$item->url('https://github.com/felixdorn')

$item->route('articles.index')
    ->activeWhenRouteMatches('articles.*') // active for articles.index / articles.edit / articles.anything

$item->meta(['a' => 'b']);
// same as
$item->a('b');
```

## Testing

```bash
composer test
```

**Navigation for Laravel** was created by **[Félix Dorn](https://twitter.com/afelixdorn)** under
the **[MIT license](https://opensource.org/licenses/MIT)**.
