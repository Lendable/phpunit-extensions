<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions;

use PHPUnit\Event;
use PHPUnit\Framework\MockObject\Generator\Generator as MockObjectGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait StrictMocking
{
    final protected function createMock(string $originalClassName): MockObject
    {
        $mock = (new MockObjectGenerator())->testDouble(
            $originalClassName,
            true,
            callOriginalConstructor: false,
            callOriginalClone: false,
            cloneArguments: false,
            allowMockingUnknownTypes: false,
            // Override: disable return value generation (default: true).
            returnValueGeneration: false,
        );

        \assert($mock instanceof $originalClassName);
        \assert($mock instanceof MockObject);

        $this->registerMockObject($mock);

        Event\Facade::emitter()->testCreatedMockObject($originalClassName);

        return $mock;
    }
}
