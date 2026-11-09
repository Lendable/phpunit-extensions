<?php

declare(strict_types=1);

namespace Tests\Unit\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\PreviousTestReleaser;
use Lendable\PHPUnitExtensions\PropertyGarbageCollection;
use Lendable\PHPUnitExtensions\StrictMocking;
use Lendable\PHPUnitExtensions\TestCase;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\NonCollectingPropertyHoldingTestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\PropertyGarbageCollectionReusingTestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\PropertyHoldingTestCase;

#[CoversTrait(PropertyGarbageCollection::class)]
#[CoversClass(PreviousTestReleaser::class)]
final class PropertyGarbageCollectionTest extends PHPUnitTestCase
{
    use StrictMocking;

    #[Test]
    public function keeps_the_properties_until_the_next_test_starts(): void
    {
        $first = new PropertyHoldingTestCase('first');
        $first->start();
        $first->service = new \stdClass();

        $first->start();
        self::assertTrue($first->isServiceInitialized());

        $second = new PropertyHoldingTestCase('second');
        $second->start();
        $second->service = new \stdClass();

        self::assertFalse($first->isServiceInitialized());
        self::assertTrue($second->isServiceInitialized());
    }

    #[Test]
    public function collects_garbage_cycles_after_releasing(): void
    {
        $first = new PropertyHoldingTestCase('first');
        $first->start();
        $first->service = $this->cyclicService();
        $runs = \gc_status()['runs'];

        new PropertyHoldingTestCase('second')->start();

        self::assertGreaterThan($runs, \gc_status()['runs']);
    }

    #[Test]
    public function garbage_cycle_collection_can_be_disabled(): void
    {
        $first = new NonCollectingPropertyHoldingTestCase('first');
        $first->start();
        $first->service = $this->cyclicService();
        $runs = \gc_status()['runs'];

        new NonCollectingPropertyHoldingTestCase('second')->start();

        self::assertSame($runs, \gc_status()['runs']);
    }

    #[Test]
    public function runs_before_every_other_before_hook(): void
    {
        $attributes = new \ReflectionMethod(TestCase::class, 'releasePreviousTestProperties')->getAttributes(Before::class);

        self::assertCount(1, $attributes);
        self::assertSame(\PHP_INT_MAX, $attributes[0]->newInstance()->priority());
    }

    #[Test]
    public function is_used_by_the_test_case(): void
    {
        self::assertContains(PropertyGarbageCollection::class, \class_uses(TestCase::class));
    }

    #[Test]
    public function can_be_used_again_below_the_test_case(): void
    {
        self::assertContains(PropertyGarbageCollection::class, \class_uses(PropertyGarbageCollectionReusingTestCase::class));
    }

    private function cyclicService(): \stdClass
    {
        $service = new \stdClass();
        $service->self = $service;

        return $service;
    }
}
