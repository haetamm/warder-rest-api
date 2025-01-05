<?php

namespace App\Http\Response;

class UserResponse
{
    public static function formatUser($user)
    {
        $shop_name = $user->sellers ? $user->sellers->shop_name : null;
        $shop_domain = $user->sellers ? $user->sellers->shop_domain : null;
        return [
            'name' => $user->profile->name,
            'shop_name' => $shop_name,
            'shop_domain' => $shop_domain,
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

    public static function formatLoginUser($token)
    {
        return [
            'token' => $token,
        ];
    }
}
