<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions;

/**
 * @internal
 */
final class PropertyReleaser
{
    public static function release(object $object): void
    {
        for ($class = new \ReflectionObject($object); $class !== false; $class = $class->getParentClass()) {
            if (\str_starts_with($class->getName(), 'PHPUnit\\')) {
                break;
            }

            foreach ($class->getProperties() as $property) {
                if (
                    $property->isStatic()
                    || $property->isReadOnly()
                    || $property->getDeclaringClass()->getName() !== $class->getName()
                ) {
                    continue;
                }

                $name = $property->getName();
                \Closure::bind(static function (object $object) use ($name): void {
                    unset($object->{$name}); // @phpstan-ignore property.dynamicName
                }, null, $class->getName())($object);
            }
        }

        \gc_collect_cycles();
    }
}
