<?php

declare(strict_types=1);

namespace Tests\Phpstan\Lendable\PHPUnitExtensions\Rule;

use Lendable\PHPUnitExtensions\Phpstan\Rule\ForbidLooseMock;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ForbidLooseMock::class)]
final class ForbidLooseMockTest extends RuleTestCase
{
    #[Test]
    public function reports_strict_test_case(): void
    {
        $this->analyse([__DIR__.'/../data/StrictMockingTestCaseTest.php'], [
            [
                'Forbidden call to "createMock", use "createStrictMock" instead.',
                15,
            ],
        ]);
    }

    #[Test]
    public function reports_strict_mocking_test_case_using_trait(): void
    {
        $this->analyse([__DIR__.'/../data/StrictMockingTraitTest.php'], [
            [
                'Forbidden call to "createMock", use "createStrictMock" instead.',
                18,
            ],
        ]);
    }

    #[Test]
    public function reports_strict_mocking_test_case_with_a_test_coming_from_a_trait(): void
    {
        $this->analyse([__DIR__.'/../data/TestThroughTraitTest.php', __DIR__.'/../data/TraitWithTest.php'], [
            [
                'Forbidden call to "createMock", use "createStrictMock" instead.',
                14,
            ],
        ]);
    }

    #[Test]
    public function does_not_report_non_strict_mocking_test_case(): void
    {
        $this->analyse([__DIR__.'/../data/TestCaseTest.php'], []);
    }

    #[Test]
    public function does_not_report_test_indirectly_extending_phpunits_test_case(): void
    {
        $this->analyse([__DIR__.'/../data/TestCaseTest.php'], []);
    }

    #[Test]
    public function does_not_report_test_using_disabling_of_return_value_generation_attribute(): void
    {
        $this->analyse([__DIR__.'/../data/TestCaseWithAttributeTest.php'], []);
    }

    protected function getRule(): ForbidLooseMock
    {
        return new ForbidLooseMock();
    }
}
