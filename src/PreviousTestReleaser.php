<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions;

/**
 * @internal
 */
final class PreviousTestReleaser
{
    private static ?object $held = null;

    public static function releasePreviousAndHold(object $test, bool $collectGarbageCycles): void
    {
        $previous = self::$held;
        self::$held = $test;

        if ($previous === null || $previous === $test) {
            return;
        }

        PropertyReleaser::release($previous);

        if ($collectGarbageCycles) {
            \gc_collect_cycles();
        }
    }
}
