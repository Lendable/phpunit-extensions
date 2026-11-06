<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\TestCase;

final class PropertyHoldingTestCase extends TestCase
{
    public ?\stdClass $service = null;

    public function exerciseRelease(): void
    {
        $this->releaseProperties();
    }

    public function isServiceInitialized(): bool
    {
        return new \ReflectionProperty($this, 'service')->isInitialized($this);
    }
}
