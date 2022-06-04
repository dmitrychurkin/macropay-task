<?php

namespace App\Services\VendingMachine\Interfaces;

interface VendingMachine
{
    public function addRecipe(Recipe $recipe): VendingMachine;

    public function clearProcess(): bool;

    public function makeRecipe(string $recipe, array $banknotes, string $currency = '$'): bool;

    public function getReceipt(): int;
}
