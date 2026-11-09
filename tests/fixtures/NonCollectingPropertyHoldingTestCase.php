<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

final class NonCollectingPropertyHoldingTestCase extends PropertyHoldingTestCase
{
    protected function collectsGarbageCyclesAfterRelease(): bool
    {
        return false;
    }
}
