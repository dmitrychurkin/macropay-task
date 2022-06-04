<?php

namespace App\Services\VendingMachine\Entities;

use App\Services\VendingMachine\Enums\Recipes;
use App\Services\VendingMachine\Exceptions\InvalidRecipeException;
use App\Services\VendingMachine\Exceptions\NotEnoughIngredientAmountException;
use App\Services\VendingMachine\Interfaces\{Ingredient, Recipe as IRecipe};

final class Recipe implements IRecipe
{
    private const COOKING_DURATION = 10;

    private $name;
    private $schema = [
        // [$ingredient, $requiredAmount]
    ];
    private $price;

    public function __construct($name, $schema, $price)
    {
        $this->name = $this->validateRecipeName($name);
        $this->schema = $schema;
        $this->price = $price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function cook(): bool
    {
        foreach ($this->schema as list($ingredient, $requiredAmount)) {
            $this->validateIngredientAmount($ingredient, $requiredAmount);
            $ingredient->take($requiredAmount);
        }

        sleep(Recipe::COOKING_DURATION);

        return true;
    }

    private function validateIngredientAmount(Ingredient $ingredient, float $requiredAmount): bool
    {
        if ($ingredient->getAmount() < $requiredAmount) {
            throw new NotEnoughIngredientAmountException(
                "Required amount of the ingredient {$ingredient->getName()} is {$requiredAmount} not enough to make recipe {$this->name} (php artisal optimize:clear  will help you)"
            );
        }
        return true;
    }

    private function validateRecipeName(string $name): string
    {
        if (property_exists(Recipes::class, $name)) {
            throw new InvalidRecipeException(
                "Invalid reciepe"
            );
        }

        return $name;
    }
}
