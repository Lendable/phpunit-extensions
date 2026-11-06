<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait PropertyGarbageCollection
{
    #[After]
    final protected function unsetAllProperties(): void
    {
        $reflection = new \ReflectionObject($this);

        foreach ($reflection->getProperties() as $property) {
            if ($property->isStatic() || \str_starts_with($property->getDeclaringClass()->getName(), 'PHPUnit\\')) {
                continue;
            }

            unset($this->{$property->getName()}); // @phpstan-ignore-line
        }

        while (($parent = $reflection->getParentClass()) !== false) {
            if (\str_starts_with($parent->getName(), 'PHPUnit\\')) {
                break;
            }

            foreach ($parent->getProperties() as $property) {
                if ($property->isStatic() || \str_starts_with($property->getDeclaringClass()->getName(), 'PHPUnit\\')) {
                    continue;
                }

                (function () use ($property): void {
                    unset($this->{$property->getName()}); // @phpstan-ignore-line
                })->bindTo($this, $parent->getName())->call($this);
            }
        }

        \gc_collect_cycles();
    }
}
