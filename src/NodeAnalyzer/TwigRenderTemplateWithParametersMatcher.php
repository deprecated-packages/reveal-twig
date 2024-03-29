<?php

declare(strict_types=1);

namespace Reveal\RevealTwig\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use Reveal\TemplatePHPStanCompiler\NodeAnalyzer\TemplateFilePathResolver;
use Reveal\TemplatePHPStanCompiler\ValueObject\RenderTemplateWithParameters;

final class TwigRenderTemplateWithParametersMatcher
{
    /**
     * @var \Reveal\TemplatePHPStanCompiler\NodeAnalyzer\TemplateFilePathResolver
     */
    private $templateFilePathResolver;
    public function __construct(TemplateFilePathResolver $templateFilePathResolver)
    {
        $this->templateFilePathResolver = $templateFilePathResolver;
    }

    /**
     * Must be template path + variables
     *
     * @return RenderTemplateWithParameters[]
     */
    public function match(MethodCall $methodCall, Scope $scope, string $templateSuffix): array
    {
        $firstArg = $methodCall->getArgs()[0] ?? null;
        if (! $firstArg instanceof Arg) {
            return [];
        }

        $firstArgValue = $firstArg->value;

        $resolvedTemplateFilePaths = $this->templateFilePathResolver->resolveExistingFilePaths(
            $firstArgValue,
            $scope,
            $templateSuffix
        );
        if ($resolvedTemplateFilePaths === []) {
            return [];
        }

        $parametersArray = $this->resolveParametersArray($methodCall);

        $result = [];
        foreach ($resolvedTemplateFilePaths as $resolvedTemplateFilePath) {
            $result[] = new RenderTemplateWithParameters($resolvedTemplateFilePath, $parametersArray);
        }

        return $result;
    }

    private function resolveParametersArray(MethodCall $methodCall): Array_
    {
        if (count($methodCall->getArgs()) !== 2) {
            return new Array_();
        }

        $secondArgValue = $methodCall->getArgs()[1]
            ->value;
        if (! $secondArgValue instanceof Array_) {
            return new Array_();
        }

        return $secondArgValue;
    }
}
