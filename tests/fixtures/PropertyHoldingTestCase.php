<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\TestCase;

class PropertyHoldingTestCase extends TestCase
{
    public ?\stdClass $service = null;

    public function start(): void
    {
        $this->releasePreviousTestProperties();
    }

    public function isServiceInitialized(): bool
    {
        return new \ReflectionProperty($this, 'service')->isInitialized($this);
    }
}
