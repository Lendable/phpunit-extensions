<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\data;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TestCaseTest extends TestCase
{
    #[Test]
    public function loose_mock(): void
    {
        $this->createMock(\ArrayAccess::class);
    }
}
