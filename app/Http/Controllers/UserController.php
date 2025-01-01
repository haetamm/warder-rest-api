<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Response\JsonResponse;
use App\Http\Response\UserResponse;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    public function index()
    {
        $user = User::with('profile', 'roles', 'sellers')->find(Auth::id());
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

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        if (!Hash::check($validated['password'], $this->user->password)) {
            return JsonResponse::respondFail("The current password is incorrect.");
        }

        $this->user->update([
            'password' => Hash::make($validated['newPassword']),
        ]);

        return JsonResponse::respondSuccess("Password updated successfully.");
    }
}
