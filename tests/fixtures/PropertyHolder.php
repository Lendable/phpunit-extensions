<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lendable\PHPUnitExtensions;

final class PropertyHolder extends PropertyHolderParent
{
    use PropertyHolding;

    public static ?\stdClass $static = null;

    public \stdClass $public;

    /**
     * @var list<\stdClass>
     */
    public array $array;

    public \stdClass $hooked {
        set => $value;
    }

    public bool $virtual {
        get => true;
    }

    private \stdClass $private;

    public function __construct(
        public readonly \stdClass $readonly = new \stdClass(),
    ) {
        parent::__construct();

        self::$static = new \stdClass();
        $this->public = new \stdClass();
        $this->array = [new \stdClass()];
        $this->hooked = new \stdClass();
        $this->private = new \stdClass();
        $this->privateInTrait = new \stdClass();
    }

    public function private(): \stdClass
    {
        return $this->private;
    }

    public function isInitialized(string $property): bool
    {
        return new \ReflectionProperty($this, $property)->isInitialized($this);
    }
}
