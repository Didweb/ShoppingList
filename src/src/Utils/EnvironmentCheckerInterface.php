<?php
namespace App\Utils;

interface EnvironmentCheckerInterface
{
    public function shouldShowDetails(): bool;
}