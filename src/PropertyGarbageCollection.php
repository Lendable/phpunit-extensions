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
    #[After(\PHP_INT_MIN)]
    final protected function releaseProperties(): void
    {
        PropertyReleaser::release($this);
    }
}
