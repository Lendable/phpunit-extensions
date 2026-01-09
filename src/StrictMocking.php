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
    /**
     * @template T of object
     *
     * @param class-string<T> $originalClassName
     *
     * @return MockObject&T
     */
    final protected function createStrictMock(string $originalClassName): MockObject
    {
        // Equivalent of PHPUnit\Framework\TestCase::createMock()'s call with return value generation disabled.
        $mock = (new MockObjectGenerator())->testDouble(
            $originalClassName,
            true, /* mockObject */
            [], /* methods */
            [], /* arguments */
            '', /* mockClassName */
            false, /* callOriginalConstructor */
            false, /* callOriginalClone */
            false, /* Override: returnValueGeneration disabled (default: enabled) */
        );

        \assert($mock instanceof $originalClassName);
        \assert($mock instanceof MockObject);

        $this->registerMockObject($originalClassName, $mock);

        Event\Facade::emitter()->testCreatedMockObject($originalClassName);

        return $mock;
    }
}
