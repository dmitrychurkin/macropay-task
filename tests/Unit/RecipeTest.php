<?php

namespace Tests\Unit;

use App\Services\VendingMachine\Entities\Ingredient;
use App\Services\VendingMachine\Entities\Recipe;
use App\Services\VendingMachine\Enums\Recipes;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_recipe_consumes_ingredients()
    {
        $waterIngredient = new Ingredient('water', 3.0);
        $teaIngredient = new Ingredient('tea bags', 5);

        $recipe = new Recipe(
            Recipes::TEA,
            [
                [$waterIngredient, .01],
                [$teaIngredient, 1]
            ],
            .15
        );

        $recipe->cook();

        $this->assertEquals($waterIngredient->getAmount(), 3.0 - .01);
        $this->assertEquals($teaIngredient->getAmount(), 5 - 1);
    }
}
