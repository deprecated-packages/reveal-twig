<?php

declare(strict_types=1);

namespace Reveal\RevealTwig\Tests\Rules\NoTwigRenderUnusedVariableRule\Fixture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class SkipUsedVariable extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render(__DIR__ . '/../Source/some_template_using_variable.twig', [
            'use_me' => 'some_value',
        ]);
    }
}
