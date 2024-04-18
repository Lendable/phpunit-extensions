<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\data;

use Lendable\PHPUnitExtensions\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class StrictMockingTestCaseTest extends TestCase
{
    #[Test]
    public function loose_mock(): void
    {
        $this->createMock(\ArrayAccess::class);
        $this->createStrictMock(\ArrayAccess::class);
    }
}
