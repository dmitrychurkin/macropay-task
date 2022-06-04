<?php

namespace App\Providers;

use App\Services\VendingMachine\Interfaces\VendingMachine as IVendingMachine;
use App\Services\Cashier\Cashier;
use App\Services\VendingMachine\Entities\Ingredient;
use App\Services\VendingMachine\Entities\Recipe;
use App\Services\VendingMachine\Enums\Recipes;
use App\Services\VendingMachine\VendingMachine;
use Illuminate\Support\ServiceProvider;

class VendingMachineProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(IVendingMachine::class, function() {
            $water = new Ingredient('water', 3.0);
            $teaBags = new Ingredient('tea', 5);
            $sugar = new Ingredient('sugar', 2);
            $coffie = new Ingredient('coffie-beans', 1);

            $vendingMachine = new VendingMachine(
                new Cashier()
            );

            $vendingMachine->addRecipe(
                new Recipe(
                    Recipes::COFFIE,
                    [
                        [$water, 2],
                        [$sugar, 1],
                        [$coffie, 1]
                    ],
                    10.0
                )
            );

            return $vendingMachine;
        });
    }
}
