<?php

declare(strict_types=1);

namespace Kommai\TestKit;

use LogicException;
use ReflectionClass;
use RuntimeException;
use Throwable;

class Proxy
{
    private object $object;
    private static string $class;

    public function __construct(object|string $objectOrClass)
    {
        if (is_object($objectOrClass)) {
            $this->object = $objectOrClass;
        }
        if (is_string($objectOrClass)) {
            self::$class = $objectOrClass;
        }
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
            if (!isset($this->object)) {
                throw new LogicException(sprintf('"%s" method cannot be called in static context', $name));
            }
            $reflectionMethod = (new ReflectionClass($this->object))->getMethod($name);
            $reflectionMethod->setAccessible(true);
            return $reflectionMethod->invokeArgs($this->object, $arguments);
        } catch (Throwable $thrown) {
            throw new RuntimeException(sprintf('Failed to call "%s" method', $name), 0, $thrown);
        }
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        try {
            if (!isset(self::$class)) {
                throw new LogicException(sprintf('"%s" method cannot be called in object context', $name));
            }
            $reflectionMethod = (new ReflectionClass(self::$class))->getMethod($name);
            $reflectionMethod->setAccessible(true);
            return $reflectionMethod->invokeArgs(null, $arguments);
        } catch (Throwable $thrown) {
            throw new RuntimeException(sprintf('Failed to call "%s" method', $name), 0, $thrown);
        }
    }
}
