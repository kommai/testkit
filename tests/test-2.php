<?php

declare(strict_types=1);

use Kommai\TestKit\Proxy;

require_once __DIR__ . '/../vendor/autoload.php';

class ExampleClass2
{
    private static int $hiddenNumber = 10;

    private static function add(int $value): int
    {
        return self::$hiddenNumber + $value;
    }
}

//var_dump(ExampleClass2::add(1));
$proxy = new Proxy(ExampleClass2::class);
var_dump($proxy::add(1));
