<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\data;

use PHPUnit\Framework\Attributes\Test;

class IndirectlyExtendingTest extends TestCaseTest
{
    #[Test]
    #[\Override]
    public function loose_mock(): void
    {
        $this->createMock(\ArrayAccess::class);
    }
}
