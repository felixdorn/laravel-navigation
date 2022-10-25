<?php

use Orchestra\Testbench\TestCase;

uses(TestCase::class)->in('.');

function testItem(string $name, string $url = null, bool $active = false): array
{
    return [
        'name'   => $name,
        'url'    => $url,
        'active' => $active,
    ];
}
