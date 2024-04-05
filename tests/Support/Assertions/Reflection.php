<?php

namespace Tests\Support\Assertions;

use ReflectionMethod;
use Tests\TestBase;

/**
 * @mixin TestBase
 * @see TestBase
 */
trait Reflection
{

    public function assertHasMethod(string $expected, object $object, ?string $customMessage = null): void
    {
        static::assertThat($object, static::callback(function (object $object) use ($expected, $customMessage) {
            try {
                new ReflectionMethod($object, $expected);
            } catch (\ReflectionException $exception) {
                if ($customMessage) {
                    static::fail($customMessage);
                } else {
                    static::fail(sprintf('Failed asserting that %s has method %s', get_class($object), $expected));
                }
            }

            return true;
        }));
    }

}
