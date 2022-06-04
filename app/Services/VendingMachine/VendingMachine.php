<?php

namespace App\Services\VendingMachine;

use App\Services\Cashier\Entities\Banknote;
use App\Services\Cashier\Interfaces\Cashier;
use App\Services\VendingMachine\Exceptions\DrinkCantBePreparedException;
use App\Services\VendingMachine\Exceptions\WorkInProgressException;
use App\Services\VendingMachine\Interfaces\{VendingMachine as IVendingMachine, Recipe};
use Illuminate\Support\Facades\Cache;

final class VendingMachine implements IVendingMachine
{
    private $cashierService;
    private $recipesPool;

    public function __construct(Cashier $cashier, array $recipes = [])
    {
        $this->cashierService = $cashier;
        $this->recipesPool = $recipes;
    }

    public function addRecipe(Recipe $recipe): IVendingMachine
    {
        $this->recipesPool[$recipe->getName()] = $recipe;

        return $this;
    }

    public function makeRecipe(string $recipe, array $banknotes, string $currency = '$'): bool
    {
        $this->validateProcess();

        $this->addBanknotes($banknotes, $currency);

        $recipeToCook = $this->getRecipe($recipe);

        $this->setProgressLock(true);

        $this->cashierService->pay($recipeToCook->getPrice());

        $result = $recipeToCook->cook();

        $this->setProgressLock(false);

        return $result;
    }

    public function getReceipt(): int
    {
        return $this->cashierService->getBalance();
    }

    public function clearProcess(): bool
    {
        return $this->setProgressLock(false);
    }

    private function addBanknotes(array $banknotes, string $currency)
    {
        foreach ($banknotes as $nominal) {
            $this->cashierService->addBanknote(
                new Banknote(intval($nominal), $currency)
            );
        }
    }

    private function getRecipe(string $recipe): Recipe
    {
        $this->validateRecipe($recipe);

        return $this->recipesPool[$recipe];
    }

    private function setProgressLock($progress): bool
    {
        if ($progress) {
            return Cache::put('vendingMachineProgressLock', true);
        }
        return Cache::delete('vendingMachineProgressLock');
    }

    private function validateRecipe(string $recipe): bool
    {
        if (!isset($this->recipesPool[$recipe])) {
            throw new DrinkCantBePreparedException(
                "Sorry today this recipe not in our menu"
            );
        }

        return true;
    }

    private function validateProcess(): bool
    {
        if (Cache::has('vendingMachineProgressLock')) {
            throw new WorkInProgressException(
                'Please wait until vending machine complete work'
            );
        }

        return true;
    }
}
