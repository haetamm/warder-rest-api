<?php

namespace App\Http\Controllers;

use App\Response\UserResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return UserResponse::formatUser($user);
    }
}
