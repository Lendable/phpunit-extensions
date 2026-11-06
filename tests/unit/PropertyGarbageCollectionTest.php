<?php

declare(strict_types=1);

namespace Tests\Unit\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\PropertyGarbageCollection;
use Lendable\PHPUnitExtensions\StrictMocking;
use Lendable\PHPUnitExtensions\TestCase;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\PropertyHoldingTestCase;

#[CoversTrait(PropertyGarbageCollection::class)]
final class PropertyGarbageCollectionTest extends PHPUnitTestCase
{
    use StrictMocking;

    #[Test]
    public function releases_the_test_case_properties(): void
    {
        $testCase = new PropertyHoldingTestCase('foo');
        $testCase->service = new \stdClass();

        $testCase->exerciseRelease();

        self::assertFalse($testCase->isServiceInitialized());
    }

    #[Test]
    public function runs_after_every_other_after_hook(): void
    {
        $attributes = new \ReflectionMethod(TestCase::class, 'releaseProperties')->getAttributes(After::class);

        self::assertCount(1, $attributes);
        self::assertSame(\PHP_INT_MIN, $attributes[0]->newInstance()->priority());
    }

    #[Test]
    public function is_used_by_the_test_case(): void
    {
        self::assertContains(PropertyGarbageCollection::class, \class_uses(TestCase::class));
    }
}
