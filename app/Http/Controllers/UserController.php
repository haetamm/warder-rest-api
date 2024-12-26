<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Response\JsonResponse;
use App\Http\Response\UserResponse;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with('profile')->find(Auth::id());
        return JsonResponse::respondSuccess(UserResponse::formatUser($user));
    }

    public function update(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();
        $response = null;

        $user =  User::find(Auth::id());

        if (isset($validatedData['email'])) {
            if ($user instanceof \App\Models\User) {
                $user->update([
                    'email' => $validatedData['email']
                ]);
            }
        }

        if ($user->profile instanceof Profile) {
            $user->profile->update($validatedData);
        }

        $response = isset($validatedData['email'])
            ? $response = $user->only(['email'])
            : $response = $user->profile->only(array_keys($validatedData));

        return JsonResponse::respondSuccess($response);
    }
}
