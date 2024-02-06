<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions\Phpstan\Rule;

use Lendable\PHPUnitExtensions\StrictMocking as StrictMockingTrait;
use Lendable\PHPUnitExtensions\TestCase as StrictMockingTestCase;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @implements Rule<Class_>
 */
final class EnforceStrictMocking implements Rule
{
    /**
     * @var array<class-string, int>
     */
    private readonly array $pardoned;

    /**
     * @param list<class-string> $pardoned
     */
    public function __construct(array $pardoned)
    {
        $this->pardoned = \array_flip($pardoned);
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->namespacedName instanceof Name) {
            return [];
        }

        if (!$node->extends instanceof Name) {
            return [];
        }

        if ($node->isAbstract()) {
            return [];
        }

        $className = $node->namespacedName->toString();
        if (!\str_ends_with($className, 'Test')) {
            return [];
        }

        if (isset($this->pardoned[$className])) {
            return [];
        }

        $reflection = $scope->resolveTypeByName($node->namespacedName)->getClassReflection();
        if (!$reflection instanceof ClassReflection) {
            return [];
        }

        $parents = $reflection->getParentClassesNames();
        if (!\in_array(TestCase::class, $parents, true)) {
            return [];
        }

        if (\in_array(StrictMockingTestCase::class, $parents, true)) {
            return [];
        }

        if (isset($reflection->getTraits(true)[StrictMockingTrait::class])) {
            return [];
        }

        return [
            RuleErrorBuilder::message(\sprintf(
                'Class "%s" must either extend "%s" or use "%s" trait.',
                $className,
                StrictMockingTestCase::class,
                StrictMockingTrait::class,
            ))->build(),
        ];
    }
}
