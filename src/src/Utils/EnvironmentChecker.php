<?php
namespace App\Utils;

class EnvironmentChecker implements EnvironmentCheckerInterface
{
    public function __construct(private string $environment)
    {
    }

    public function shouldShowDetails(): bool
    {
        return in_array($this->environment, ['dev', 'test'], true);
    }
}