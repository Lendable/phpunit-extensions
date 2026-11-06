<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

abstract class PropertyHolderParent
{
    protected \stdClass $protectedInParent;

    private \stdClass $privateInParent;

    public function __construct()
    {
        $this->protectedInParent = new \stdClass();
        $this->replacePrivateInParent(new \stdClass());
    }

    final public function replacePrivateInParent(\stdClass $privateInParent): void
    {
        $this->privateInParent = $privateInParent;
    }

    final public function privateInParent(): \stdClass
    {
        return $this->privateInParent;
    }

    final public function isPrivateInParentInitialized(): bool
    {
        return new \ReflectionProperty(self::class, 'privateInParent')->isInitialized($this);
    }
}
