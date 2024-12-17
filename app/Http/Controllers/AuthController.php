<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use Illuminate\Support\Str;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;


class AuthController extends Controller
{

    /** @var GoogleProvider */
    private $googleProvider;

    public function __construct()
    {
        $this->googleProvider = Socialite::driver('google');
    }

    protected function createUserWithRoles($email, $password = null)
    {
        if (!$password) {
            $password = Str::random(8, 'alnum');
        }

        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $role = Role::where('name', 'USER')->first();
        $user->roles()->attach($role);

        return $user;
    }

    public function redirectToGoogle()
    {
        return response()->json([
            'url' => $this->googleProvider->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function handleGoogleCallback()
    {
        DB::beginTransaction();

        try {
            $googleUser = $this->googleProvider->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();
            if (!$user) {
                $user = $this->createUserWithRoles($googleUser->getEmail());
            }

            $token = Auth::guard('api')->login($user, true);
            DB::commit();

            return JsonResponse::respondSuccess([
                'token' => $token,
                'roles' => $user->roles->pluck('name'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponse::respondFail('Error during Google login: ' . $e->getMessage(), 500);
        }
    }

    public function register(RegisterUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->validated();
            $this->createUserWithRoles($request->email, $request->password);
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
