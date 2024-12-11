<?php

namespace App\Response;

class UserResponse
{
    public static function formatUser($user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'roles' => $user->roles->pluck('name'),
        ];
    }
}
