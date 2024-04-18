<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\data;

use Lendable\PHPUnitExtensions\TestCase;
use PHPUnit\Framework\Attributes\DisableReturnValueGenerationForTestDoubles;
use PHPUnit\Framework\Attributes\Test;

#[DisableReturnValueGenerationForTestDoubles]
class OurTestCaseExtendingAndUsingAttributeTest extends TestCase
{
    #[Test]
    public function loose_mock(): void
    {
        $this->createMock(\ArrayAccess::class);
    }
}
