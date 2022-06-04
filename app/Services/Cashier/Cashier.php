<?php

namespace App\Services\Cashier;

use App\Services\Cashier\Interfaces\{Banknote, Cashier as ICashier};
use App\Services\Cashier\Enums\Nominals;
use App\Services\Cashier\Exceptions\InvalidBanknoteValueException;
use App\Services\Cashier\Exceptions\NotEnoughMoneyException;

final class Cashier implements ICashier
{
    private $banknotesPool = [];

    public function __construct($banknotesPool = [
        Nominals::ONE  => [],
        Nominals::FIVE => [],
        Nominals::TEN  => []
    ])
    {
        $this->banknotesPool = $banknotesPool;
    }

    public function getBalance(): int
    {
        $amount = 0;
        $sum = function(int $total, Banknote $banknote) {
            return $total + $banknote->getNominal();
        };

        foreach($this->banknotesPool as $nominal => $banknotesArray) {
            $amount += array_reduce($banknotesArray, $sum, 0);
        }

        return $amount;
    }

    public function addBanknote(Banknote $banknote): ICashier
    {
        $this->validateBanknote($banknote);
        $this->banknotesPool[$banknote->getNominal()][] = $banknote;

        return $this;
    }

    public function pay(float $price): void
    {
        $this->validateBalance($price);

        $reversedBanknotesPool = array_reverse($this->banknotesPool, true);
        $payedAmount = 0;
        $nominalsCount = count($reversedBanknotesPool);

        $handleOperation = function($banknote, $payedAmount) {
            $this->removeBanknote($banknote);

            return $payedAmount + $banknote->getNominal();
        };

        foreach ($reversedBanknotesPool as $key => $banknotes) {
            $nominal = intval($key);
            for ($i = 0; $i < count($banknotes); $i++) {
                $banknote = $banknotes[$i];
                if ($nominalsCount == 1) {
                    if ($payedAmount < $price) {
                        $payedAmount = $handleOperation($banknote, $payedAmount);
                    }
                }else {
                    if (($payedAmount + $nominal) <= $price) {
                        $payedAmount = $handleOperation($banknote, $payedAmount);
                    }
                }
            }
            $nominalsCount--;
        }
    }

    public function removeBanknote(Banknote $banknote, ?int $amount = null): ICashier
    {
        $amount = $amount ?: 1;

        $banknotes = &$this->banknotesPool[$banknote->getNominal()];

        array_splice($banknotes, 0, $amount);

        return $this;
    }

    private function validateBalance(float $price)
    {
        if ($price > $this->getBalance()) {
            throw new NotEnoughMoneyException(
                "Not enough money to prepare this recipe"
            );
        }

        return true;
    }

    private function validateBanknote(Banknote $banknote): bool
    {
        if (!array_key_exists($banknote->getNominal(), $this->banknotesPool)) {
            throw new InvalidBanknoteValueException(
                "Invalid banknote {$banknote->getNominal()}"
            );
        }

        return true;
    }
}
