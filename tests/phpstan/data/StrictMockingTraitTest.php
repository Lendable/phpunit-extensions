<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\data;

use Lendable\PHPUnitExtensions\StrictMocking;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StrictMockingTraitTest extends TestCase
{
    use StrictMocking;

    #[Test]
    public function loose_mock(): void
    {
        $this->createMock(\ArrayAccess::class);
        $this->createStrictMock(\ArrayAccess::class);
    }
}
