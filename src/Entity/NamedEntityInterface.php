<?php

declare(strict_types=1);

namespace App\Entity;

interface NamedEntityInterface
{
    public function getName(): string;
}