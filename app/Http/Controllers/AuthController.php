<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->validated();
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $role = Role::where('name', 'USER')->first();
            $user->roles()->attach($role);
            DB::commit();

            return JsonResponse::respondSuccess('User Registration succesfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponse::respondFail('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!$token = JWTAuth::attempt($credentials)) {
            return JsonResponse::respondFail('Provided email or password is incorrect', 401);
        }

        $user = Auth::user();
        return JsonResponse::respondSuccess([
            'token' => $token,
            'roles' => $user->roles->pluck('name'),
        ], 200);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User logged out successfully']);
    }
}
