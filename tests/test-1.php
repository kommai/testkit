<?php

declare(strict_types=1);

use Kommai\TestKit\Proxy;

require_once __DIR__ . '/../vendor/autoload.php';

class ExampleClass1
{
    protected int $hiddenNumber = 9;

    private function add(int $a, int $b): int
    {
        return $this->hiddenNumber + $a + $b;
    }
}

$proxy = new Proxy(new ExampleClass1());
var_dump($proxy->hiddenNumber);
var_dump($proxy->add(1, 2));
