<?php

declare(strict_types=1);

namespace Reveal\RevealTwig\Tests\Rules\NoTwigRenderUnusedVariableRule;

use Iterator;
use PHPStan\Rules\Rule;
use Reveal\RevealTwig\Rules\NoTwigRenderUnusedVariableRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<NoTwigRenderUnusedVariableRule>
 */
final class NoTwigRenderUnusedVariableRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string|int> $expectedErrorMessagesWithLines
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/RenderWithUnusedVariable.php', [
            [sprintf(NoTwigRenderUnusedVariableRule::ERROR_MESSAGE, 'unused_variable'), 14],
        ]];

        yield [__DIR__ . '/Fixture/RenderBareTwigWithUnusedVariable.php', [
            [sprintf(NoTwigRenderUnusedVariableRule::ERROR_MESSAGE, 'unused_variable'), 13],
        ]];

        yield [__DIR__ . '/Fixture/SkipUsedVariable.php', []];
        yield [__DIR__ . '/Fixture/SkipUnionSingleUsed.php', []];
        yield [__DIR__ . '/Fixture/SkipForeachUsedVariable.php', []];
        yield [__DIR__ . '/Fixture/SkipIncludeArray.php', []];
        yield [__DIR__ . '/Fixture/SkipControllerRouter.php', []];

        yield [__DIR__ . '/Fixture/RenderTwoTemplates.php', [
            [sprintf(NoTwigRenderUnusedVariableRule::ERROR_MESSAGE, 'unused_variable'), 16],
        ]];
    }

    protected function getRule(): \Reveal\RevealTwig\Rules\NoTwigRenderUnusedVariableRule
    {
        return $this->getRuleFromConfig(
            NoTwigRenderUnusedVariableRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
