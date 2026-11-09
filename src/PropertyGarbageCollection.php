<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait PropertyGarbageCollection
{
    #[Before(\PHP_INT_MAX)]
    protected function releasePreviousTestProperties(): void
    {
        PreviousTestReleaser::releasePreviousAndHold($this, $this->collectsGarbageCyclesAfterRelease());
    }

    protected function collectsGarbageCyclesAfterRelease(): bool
    {
        return true;
    }
}
