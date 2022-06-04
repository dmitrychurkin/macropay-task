<?php

namespace App\Services\Cashier\Interfaces;

interface Banknote
{
    public function getCurrency(): string;

    public function getNominal(): int;
}
