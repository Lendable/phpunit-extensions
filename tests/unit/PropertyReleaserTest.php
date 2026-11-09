<?php

declare(strict_types=1);

namespace Tests\Unit\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\PropertyReleaser;
use Lendable\PHPUnitExtensions\StrictMocking;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Lendable\PHPUnitExtensions\PropertyHolder;

#[CoversClass(PropertyReleaser::class)]
final class PropertyReleaserTest extends TestCase
{
    use StrictMocking;

    #[Test]
    public function releases_properties_of_every_visibility_declared_anywhere_in_the_class_hierarchy(): void
    {
        $holder = new PropertyHolder();

        PropertyReleaser::release($holder);

        self::assertFalse($holder->isInitialized('public'));
        self::assertFalse($holder->isInitialized('array'));
        self::assertFalse($holder->isInitialized('private'));
        self::assertFalse($holder->isInitialized('privateInTrait'));
        self::assertFalse($holder->isInitialized('protectedInParent'));
        self::assertFalse($holder->isPrivateInParentInitialized());
    }

    #[Test]
    public function keeps_readonly_static_and_hooked_properties(): void
    {
        $holder = new PropertyHolder();

        PropertyReleaser::release($holder);

        self::assertTrue($holder->isInitialized('readonly'));
        self::assertNotNull(PropertyHolder::$static);
        self::assertTrue($holder->isInitialized('hooked'));
        self::assertTrue($holder->virtual);
    }
}
