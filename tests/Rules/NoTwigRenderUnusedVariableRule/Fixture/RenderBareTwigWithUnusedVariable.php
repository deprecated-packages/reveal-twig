<?php

declare(strict_types=1);

namespace Reveal\RevealTwig\Tests\Rules\NoTwigRenderUnusedVariableRule\Fixture;

use Twig\Environment;

final class RenderBareTwigWithUnusedVariable
{
    public function run(Environment $environment)
    {
        $environment->render(__DIR__ . '/../Source/some_template.twig', [
            'unused_variable' => 'some_value',
        ]);
    }
}
