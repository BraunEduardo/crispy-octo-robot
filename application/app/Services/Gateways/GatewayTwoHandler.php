<?php

namespace App\Services\Gateways;

use App\Models\Gateway;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GatewayTwoHandler extends AbstractGatewayHandler
{
    private PendingRequest $http;

    public function __construct(Gateway $gateway) {
        $this->http = Http::baseUrl($gateway->url)
            ->withHeaders([
                'Gateway-Auth-Token' => $gateway->data['token'],
                'Gateway-Auth-Secret' => $gateway->data['secret'],
            ]);
    }

    public function charge(Transaction $transaction, array $card): bool {
        $response = $this->http->post('transacoes', [
            'valor' => intval($transaction->amount * 100),
            'nome' => $transaction->clientRelation->name,
            'email' => $transaction->clientRelation->email,
            'numeroCartao' => $card['numbers'],
            'cvv' => $card['cvv'],
        ]);

        if ( $response->failed() )
            throw new Exception($response->json('error'), $response->status());

        $errors = $response->json('erros');

        if ( $errors !== null ) {
            $status = $response->json('statusCode');
            $messages = [];

            foreach ($errors as $error) {
                $messages[] = $error['message'];
            }

            $message = implode('. ', $messages);

            throw new Exception($message, $status);
        }

        return true;
    }
    public function chargeBack(Transaction $transaction): bool {
        return false;
    }
}
