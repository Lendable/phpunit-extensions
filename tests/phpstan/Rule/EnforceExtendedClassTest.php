<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\Rule;

use Lendable\PHPUnitExtensions\Phpstan\Rule\EnforceStrictMocking;
use Lendable\PHPUnitExtensions\StrictMocking;
use Lendable\PHPUnitExtensions\TestCase;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Phpstan\Lendable\PHPUnitExtensions\data\IndirectlyExtendingTest;
use Tests\Phpstan\Lendable\PHPUnitExtensions\data\TestCaseTest;

#[CoversClass(EnforceStrictMocking::class)]
final class EnforceExtendedClassTest extends RuleTestCase
{
    #[Test]
    public function reports_test_directly_extending_phpunits_test_case(): void
    {
        $this->analyse([__DIR__.'/../data/TestCaseTest.php'], [
            [
                $this->errorMessageFor(TestCaseTest::class),
                9,
            ],
        ]);
    }

    #[Test]
    public function does_not_report_abstract_test_directly_extending_phpunits_test_case(): void
    {
        $this->analyse([__DIR__.'/../data/AbstractTestCaseTest.php'], []);
    }

    #[Test]
    public function reports_test_indirectly_extending_phpunits_test_case(): void
    {
        $this->analyse([__DIR__.'/../data/IndirectlyExtendingTest.php'], [
            [
                $this->errorMessageFor(IndirectlyExtendingTest::class),
                7,
            ],
        ]);
    }

    #[Test]
    public function does_not_report_test_extending_strict_mocking(): void
    {
        $this->analyse([__DIR__.'/../data/StrictMockingTestCaseTest.php'], []);
    }

    #[Test]
    public function does_not_report_test_directly_using_strict_mocking_trait(): void
    {
        $this->analyse([__DIR__.'/../data/StrictMockingTraitTest.php'], []);
    }

    #[Test]
    public function does_not_report_test_indirectly_using_strict_mocking_trait(): void
    {
        $this->analyse([__DIR__.'/../data/IndirectStrictMockingTraitTest.php'], []);
    }

    protected function getRule(): EnforceStrictMocking
    {
        return new EnforceStrictMocking([]);
    }

    private function errorMessageFor(string $class): string
    {
        return \sprintf(
            'Class "%s" must either extend "%s" or use "%s" trait.',
            $class,
            TestCase::class,
            StrictMocking::class,
        );
    }
}
