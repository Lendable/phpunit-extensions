<?php

declare(strict_types=1);

namespace Tests\Unit\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\StrictMocking;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\ExampleInterface;

#[CoversClass(StrictMocking::class)]
final class StrictMockingTest extends TestCase
{
    use StrictMocking;

    #[Test]
    public function mocks_created_require_methods_to_be_configured_explicitly(): void
    {
        $testCase = new class ('foo') extends TestCase {
            use StrictMocking;

            public function exercise(): void
            {
                $mock = $this->createStrictMock(ExampleInterface::class);
                $mock->method('bar')->willReturn(42);

                $mock->foo();
            }
        };

        $this->expectException(ReturnValueNotConfiguredException::class);

        $testCase->exercise();
    }
}
