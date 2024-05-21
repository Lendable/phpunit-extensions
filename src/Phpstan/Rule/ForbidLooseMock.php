<?php

declare(strict_types=1);

namespace Lendable\PHPUnitExtensions\Phpstan\Rule;

use Lendable\PHPUnitExtensions\StrictMocking as StrictMockingTrait;
use Lendable\PHPUnitExtensions\TestCase as StrictMockingTestCase;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\Attributes\DisableReturnValueGenerationForTestDoubles;
use PHPUnit\Framework\TestCase;

/**
 * @implements Rule<MethodCall>
 */
final readonly class ForbidLooseMock implements Rule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier) {
            return [];
        }

        if (!$scope->isInClass()) {
            return [];
        }

        if ($node->name->toString() !== 'createMock') {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $parents = $reflection->getParentClassesNames();
        if (!\in_array(TestCase::class, $parents, true)) {
            return [];
        }

        if (
            !\in_array(StrictMockingTestCase::class, $parents, true)
            && !isset($reflection->getTraits(true)[StrictMockingTrait::class])
        ) {
            return [];
        }

        if ($reflection->getNativeReflection()->getAttributes(DisableReturnValueGenerationForTestDoubles::class) !== []) {
            return [];
        }

        /** @var list<IdentifierRuleError> */
        return [RuleErrorBuilder::message('Forbidden call to "createMock", use "createStrictMock" instead.')->build()];
    }
}
