<?php

namespace App\Services\Gateways;

use App\Models\Transaction;

abstract class AbstractGatewayHandler
{
    abstract public function charge(Transaction $transaction, array $card): bool;

    abstract public function chargeBack(Transaction $transaction): bool;

}
