<?php

namespace Tests\Rector\Lendable\PHPUnitExtensions\Rector\Fixture;

use PHPUnit\Framework\TestCase;

final class WithoutAttributeTest extends TestCase
{
}

?>
-----
<?php

namespace Tests\Rector\Lendable\PHPUnitExtensions\Rector\Fixture;

use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\DisableReturnValueGenerationForTestDoubles]
final class WithoutAttributeTest extends TestCase
{
}

?>
