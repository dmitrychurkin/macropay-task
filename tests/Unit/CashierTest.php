<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Cashier\Cashier;
use App\Services\Cashier\Entities\Banknote;
use App\Services\Cashier\Enums\Nominals;

class CashierTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_cashier_pool_not_empty_after_adding_banknote()
    {
        $cashier = new Cashier();
        $banknote = new Banknote(Nominals::ONE);

        $cashier->addBanknote($banknote);

        $this->assertTrue($cashier->getBalance() == Nominals::ONE);
    }

    public function test_cashier_pool_empty_after_remove_banknote()
    {
        $cashier = new Cashier();
        $banknote = new Banknote(Nominals::ONE);

        $cashier->addBanknote($banknote);

        $cashier->removeBanknote($banknote);

        $this->assertTrue($cashier->getBalance() == 0);
    }

    public function test_cashier_pay_20_dollar()
    {
        $cashier = new Cashier();

        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));

        $cashier->addBanknote(new Banknote(Nominals::TEN));

        $cashier->addBanknote(new Banknote(Nominals::FIVE));

        $cashier->pay(20);

        $this->assertTrue($cashier->getBalance() == 0);
    }

    public function test_cashier_pay_22_dollar_and_get_balance()
    {
        $cashier = new Cashier();

        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));
        $cashier->addBanknote(new Banknote(Nominals::ONE));

        $cashier->addBanknote(new Banknote(Nominals::TEN));

        $cashier->addBanknote(new Banknote(Nominals::FIVE));
        $cashier->addBanknote(new Banknote(Nominals::FIVE));

        $cashier->pay(22);

        $this->assertTrue($cashier->getBalance() == 3);
    }
}
