<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

use Lendable\PHPUnitExtensions\TestCase;

final class ExampleTestCase extends TestCase
{
    public function exerciseMockCreationWithoutMethodsConfigured(): void
    {
        $mock = $this->createMock(ExampleInterface::class);

        $mock->foo();
    }
}
