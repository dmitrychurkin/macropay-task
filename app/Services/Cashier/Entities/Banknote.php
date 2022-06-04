<?php

namespace App\Services\Cashier\Entities;

use App\Services\Cashier\Interfaces\Banknote as IBanknote;

final class Banknote implements IBanknote
{
    private $nominal;
    private $currency;

    public function __construct(int $nominal, string $currency = '$')
    {
        $this->nominal = $nominal;
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getNominal(): int
    {
        return $this->nominal;
    }
}
