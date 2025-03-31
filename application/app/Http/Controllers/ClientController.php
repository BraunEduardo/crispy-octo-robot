<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return $clients;
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());

        return $client;
    }

    public function show(Client $client)
    {
        $client->load('transactions');

        return $client;
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->updateOrFail($request->validated());

        return $client;
    }

    public function destroy(int $id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        $client->delete();

        return $client;
    }
}
