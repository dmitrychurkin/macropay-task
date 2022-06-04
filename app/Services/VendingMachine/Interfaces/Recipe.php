<?php

namespace App\Services\VendingMachine\Interfaces;

interface Recipe
{
    public function getName(): string;

    public function getPrice(): float;

    public function cook(): bool;
}
