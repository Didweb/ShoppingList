<?php

namespace App;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();

        if (!Type::hasType('status')) {
            Type::addType('status', \App\Doctrine\Type\StatusType::class);
        }

        if (!Type::hasType('hex_color')) {
            Type::addType('hex_color', \App\Doctrine\Type\HexColorType::class);
        }

        if (!Type::hasType('slug')) {
            Type::addType('slug', \App\Doctrine\Type\SlugType::class);
        }
    }
}
