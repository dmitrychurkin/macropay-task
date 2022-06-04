<?php

namespace App\Services\VendingMachine\Interfaces;

interface Ingredient
{
    public function getAmount(): float;

    public function getName(): string;

    public function take(float $amount): Ingredient;
}
