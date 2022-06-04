<?php

namespace App\Services\Cashier\Interfaces;

interface Cashier
{
    public function addBanknote(Banknote $banknote): Cashier;

    public function getBalance(): int;

    public function pay(float $price): void;

    public function removeBanknote(Banknote $banknote, ?int $amount = null): Cashier;
}
