<?php

declare(strict_types=1);

use Lendable\PHPUnitExtensions\Rector\EnforceDisableReturnValueGenerationForTestDoublesRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(EnforceDisableReturnValueGenerationForTestDoublesRector::class);
};
