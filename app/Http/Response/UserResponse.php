<?php

namespace App\Http\Response;

class UserResponse
{
    public static function formatUser($user)
    {
        return [
            'name' => $user->profile->name,
            'image' => $user->profile->image,
            'birth_date' => $user->profile->birth_date,
            'gender' => $user->profile->gender,
            'email' => $user->email,
            'phone_number' => $user->profile->phone_number,
            'roles' => $user->roles->pluck('name'),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    public static function formatLoginUser($user, $token)
    {
        return [
            'name' => $user->profile->name,
            'image' => $user->profile->image,
            'token' => $token,
            'roles' => $user->roles->pluck('name'),
        ];
    }
}
