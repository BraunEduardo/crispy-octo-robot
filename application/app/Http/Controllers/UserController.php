<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return $users;
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return $user;
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->updateOrFail($request->validated());

        return $user;
    }

    public function destroy(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->delete();

        return $user;
    }
}
