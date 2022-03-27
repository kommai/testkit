<?php

declare(strict_types=1);

namespace Kommai\TestKit;

use ReflectionClass;
use RuntimeException;
use Throwable;

class Proxy
{
    private object $object;

    public function __construct(object $object)
    {
        $this->object = $object;
    }

    public function __get(string $name): mixed
    {
        try {
            $reflectionProperty = (new ReflectionClass($this->object))->getProperty($name);
            $reflectionProperty->setAccessible(true);
            return $reflectionProperty->getValue($this->object);
        } catch (Throwable $thrown) {
            throw new RuntimeException(sprintf('Failed to get "%s" property', $name), 0, $thrown);
        }
    }

    public function __call(string $name, array $arguments): mixed
    {
        try {
            $reflectionMethod = (new ReflectionClass($this->object))->getMethod($name);
            $reflectionMethod->setAccessible(true);
            return $reflectionMethod->invokeArgs($this->object, $arguments);
        } catch (Throwable $thrown) {
            throw new RuntimeException(sprintf('Failed to call "%s" method', $name), 0, $thrown);
        }
    }
}
