<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\data;

use PHPUnit\Framework\Attributes\Test;

final class IndirectStrictMockingTraitTest extends StrictMockingTraitTest
{
    #[Test]
    public function loose_mock(): void
    {
        $this->createMock(\ArrayAccess::class);
        $this->createStrictMock(\ArrayAccess::class);
    }
}
