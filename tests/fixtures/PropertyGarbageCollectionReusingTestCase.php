<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\PropertyGarbageCollection;
use Lendable\PHPUnitExtensions\TestCase;

final class PropertyGarbageCollectionReusingTestCase extends TestCase
{
    use PropertyGarbageCollection;
}
