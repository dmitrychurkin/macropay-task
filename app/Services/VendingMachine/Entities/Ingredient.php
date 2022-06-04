<?php

namespace App\Services\VendingMachine\Entities;

use Illuminate\Support\Facades\Cache;

use App\Services\VendingMachine\Interfaces\Ingredient as IIndredient;

final class Ingredient implements IIndredient
{
    private $name;

    public function __construct(string $name, float $amount)
    {
        $this->name = $name;
        //  Just to emulate persistance
        Cache::has($name) ?: Cache::put($name, $amount);
    }

    public function getAmount(): float
    {
        return Cache::get($this->name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function take(float $amount = 0): IIndredient
    {
        $remainingAmount = $this->getAmount() - $amount;

        Cache::put($this->name, $remainingAmount < 0 ? 0 : $remainingAmount);

        return $this;
    }
}
