<?php

namespace App\Console\Commands;

use App\Services\VendingMachine\Interfaces\VendingMachine;
use Exception;
use Illuminate\Console\Command;

class MakeMeDrink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drink:make
                            {drink : Name of the drink}
                            {--banknote=*}
                            {--currency=$}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making something special ....';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(VendingMachine $vendingMachine)
    {
        try {
            $vendingMachine->makeRecipe(
                $this->argument('drink'),
                $this->option('banknote'),
                $this->option('currency')
            );

            $this->info("Your balance for {$this->argument('drink')} {$vendingMachine->getReceipt()}");
        }catch (Exception $e) {
            $this->error($e->getMessage());
        }finally {
            $vendingMachine->clearProcess();
        }
    }
}
