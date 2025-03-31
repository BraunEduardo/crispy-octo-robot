<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\Gateways\AbstractGatewayHandler;

class TransactionService
{
    public function create(array $_client, array $_card, array $_products) {
        $amount = 0;
        $productSync = [];
        $client = Client::firstOrCreate($_client);
        $products = Product::find(array_column($_products, 'id'))->keyBy('id');

        foreach ($_products as $_product) {
            $product = $products->get($_product['id']);
            $amount += $product->amount * $_product['quantity'];

            $productSync[$_product['id']] = [
                'quantity' => $_product['quantity'],
            ];
        }

        $transaction = Transaction::create([
            'client' => $client->id,
            'status' => Transaction::PROCESSING,
            'amount' => $amount,
            'card_last_numbers' => substr($_card['card_numbers'], -4),
        ]);

        $transaction->products()->sync($productSync);

        $card = [
            'cvv' => str_pad((string) $_card['cvv'], 3, '0', STR_PAD_LEFT),
            'numbers' => $_card['card_numbers'],
        ];

        $this->process($transaction, $card);

        return $transaction;
    }

    private function process(Transaction $transaction, array $card) {
        $gateways = Gateway::where('is_active', true)->orderBy('priority')->get();

        if ( $gateways->isEmpty() ) {
            $transaction->update([
                'status' => Transaction::ERROR,
            ]);

            return false;
        }

        foreach ($gateways as $gateway) {
            $gatewayClass = 'App\\Services\\Gateways\\'.$gateway->name.'Handler';

            if ( !class_exists($gatewayClass) ) {
                $status = Transaction::ERROR;

                continue;
            }

            $gatewayHanlder = new $gatewayClass($gateway);

            if ( !($gatewayHanlder instanceof AbstractGatewayHandler) ) {
                $status = Transaction::ERROR;

                continue;
            }

            if ($gatewayHanlder->charge($transaction, $card)) {
                $status = Transaction::PROCESSED;

                break;
            }

            $status = Transaction::DENIED;
        }

        $transaction->update([
            'gateway' => $gateway->id,
            'status' => $status,
        ]);
    }
}
