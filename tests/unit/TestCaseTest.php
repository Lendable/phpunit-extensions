<?php

declare(strict_types=1);

namespace Tests\Unit\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\ExampleTestCase;

#[CoversClass(TestCase::class)]
final class TestCaseTest extends PHPUnitTestCase
{
    #[Test]
    public function mocks_created_require_methods_to_be_configured_explicitly(): void
    {
        $testCase = new ExampleTestCase('foo');

        $this->expectException(ReturnValueNotConfiguredException::class);

        $testCase->exerciseMockCreationWithoutMethodsConfigured();
    }
}
