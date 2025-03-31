<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriorityGatewayRequest;
use App\Http\Requests\UpdateGatewayRequest;
use App\Models\Gateway;

class GatewayController extends Controller
{
    public function index()
    {
        $gateways = Gateway::all();

        return $gateways;
    }

    public function show(Gateway $gateway)
    {
        return $gateway;
    }

    public function update(UpdateGatewayRequest $request, Gateway $gateway)
    {
        $gateway->updateOrFail($request->validated());

        return $gateway;
    }

    public function destroy(int $id)
    {
        $gateway = Gateway::withTrashed()->findOrFail($id);

        $gateway->delete();

        return $gateway;
    }

    public function toggle(Gateway $gateway)
    {
        $gateway->updateOrFail([
            'is_active' => !$gateway->is_active,
        ]);

        return $gateway;
    }

    public function changePriority(Gateway $gateway, PriorityGatewayRequest $request)
    {
        $priority = $request->validated('priority');

        $gateway->updateOrFail([
            'priority' => $priority,
        ]);

        return $gateway;
    }
}
