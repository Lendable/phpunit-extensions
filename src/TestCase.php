<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    use StrictMocking;
}
