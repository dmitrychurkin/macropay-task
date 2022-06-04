
If you need to add more ingredients / recipes, you can always do that from
VendingMachineProvider.php
#### Usage
**Please note, this is a CLI utility app**
1. Open terminal
2. ```sh
    php artisan drink:make {nameOfDrink} {--banknote=1|5|10} [{--banknote=1|5|10}... {--currency='US'}]
```
Avalaible drinks:
check file App\Services\VendingMachine\Enums\Recipes.php
feel free to extend

Acceptable banknotes:
check file file App\Services\Cashier\Enums\Nominals.php
