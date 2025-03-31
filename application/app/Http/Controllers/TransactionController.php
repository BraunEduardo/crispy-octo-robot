<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Client;
use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();

        return $transactions;
    }

    public function store(StoreTransactionRequest $request, TransactionService $service)
    {
        $client = $request->safe()->only(['name', 'email']);
        $card = $request->safe()->only(['card_numbers', 'cvv']);
        $products = $request->validated('products');

        $transaction = $service->create($client, $card, $products);

        return $transaction;
    }

    public function show(Transaction $transaction)
    {
        return $transaction;
    }

    public function destroy(int $id)
    {
        $transaction = Transaction::withTrashed()->findOrFail($id);

        $transaction->delete();

        return $transaction;
    }

    public function chargeBack(Transaction $transaction)
    {
        if ( $transaction->status !== Transaction::REFUNDED ) {
            $transaction->update([
                'status' => Transaction::REFUNDED,
            ]);

            
        }

        return $transaction;
    }
}
