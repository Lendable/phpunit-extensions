<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

trait PropertyHolding
{
    private \stdClass $privateInTrait;

    public function privateInTrait(): \stdClass
    {
        return $this->privateInTrait;
    }
}
