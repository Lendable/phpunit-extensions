<?php

declare(strict_types=1);

namespace Tests\Rector\Lendable\PHPUnitExtensions\Rector;

use Lendable\PHPUnitExtensions\Rector\EnforceDisableReturnValueGenerationForTestDoublesRector;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

#[CoversClass(EnforceDisableReturnValueGenerationForTestDoublesRector::class)]
final class EnforceDisableReturnValueGenerationForTestDoublesRectorTest extends AbstractRectorTestCase
{
    public function provideConfigFilePath(): string
    {
        return __DIR__.'/config/configured_rule.php';
    }

    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
}
