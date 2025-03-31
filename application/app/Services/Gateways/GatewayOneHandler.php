<?php

namespace App\Services\Gateways;

use App\Models\Gateway;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GatewayOneHandler extends AbstractGatewayHandler
{
    private Gateway $gateway;

    private PendingRequest $http;

    public function __construct(Gateway $gateway) {
        $this->gateway = $gateway;
        $this->http = Http::baseUrl($gateway->url);
    }

    private function login() {
        $response = $this->http->post('login', [
            'email' => $this->gateway->data['email'],
            'token' => $this->gateway->data['token'],
        ]);

        $token = $response->json('token');

        $this->http = $this->http->withToken($token);
    }

    public function charge(Transaction $transaction, array $card): bool {
        $this->login();

        $response = $this->http->post('transactions', [
            'amount' => intval($transaction->amount * 100),
            'name' => $transaction->clientRelation->name,
            'email' => $transaction->clientRelation->email,
            'cardNumber' => $card['numbers'],
            'cvv' => $card['cvv'],
        ]);

        if ( $response->status() === 400 )
            throw new Exception(implode('. ', $response->json('*.message')), 422);

        if ( $response->failed() )
            throw new Exception($response->json('error'), $response->status());

        return true;
    }
    public function chargeBack(Transaction $transaction): bool {
        return false;
    }
}
