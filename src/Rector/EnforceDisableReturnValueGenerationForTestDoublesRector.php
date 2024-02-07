<?php

namespace Lendable\PHPUnitExtensions\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\Attributes\DisableReturnValueGenerationForTestDoubles;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EnforceDisableReturnValueGenerationForTestDoublesRector extends AbstractRector
{
    public function __construct(
        private readonly PhpAttributeAnalyzer $attributeAnalyzer,
        private readonly TestsNodeAnalyzer $testsNodeAnalyzer,
        private readonly PhpAttributeGroupFactory $attributeGroupFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the `DisableReturnValueGenerationForTestDoubles` attribute to test cases',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
namespace Tests\Foo;

use PHPUnit\Framework\TestCase;

class FooTest extends TestCase {
}
CODE_SAMPLE
                    , <<<'CODE_SAMPLE'
namespace Tests\Foo;

use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\DisableReturnValueGenerationForTestDoubles]
class FooTest extends TestCase {
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Class_
    {
        if (!$this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }

        if ($this->attributeAnalyzer->hasPhpAttribute($node, DisableReturnValueGenerationForTestDoubles::class)) {
            return null;
        }

        $node->attrGroups[] = $this->attributeGroupFactory->createFromClass(DisableReturnValueGenerationForTestDoubles::class);

        return $node;
    }
}
