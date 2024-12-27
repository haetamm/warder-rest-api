<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Response\JsonResponse;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        $addresses = Address::where('user_id', $user->id)->get();
        return JsonResponse::respondSuccess($addresses);
    }

    public function store(AddressRequest $request)
    {
        $validated = $request->validated();

        $user = User::find(Auth::id());

        if ($validated['selected']) {
            Address::where('user_id', $user->id)->update(['selected' => false]);
        }

        $address = $user->addresses()->create($validated);

        return JsonResponse::respondSuccess($address);
    }

    public function getById($id)
    {
        $user = User::find(Auth::id());

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return JsonResponse::respondErrorNotFound("Address not found");
        }
        return JsonResponse::respondSuccess($address);
    }

    public function update(AddressRequest $request, $id)
    {
        $validated = $request->validated();

        $user = User::find(Auth::id());

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return JsonResponse::respondErrorNotFound("Address not found");
        }

        if (isset($validated['selected']) && $validated['selected']) {
            Address::where('user_id', $user->id)->update(['selected' => false]);
        }

        $address->update($validated);

        return JsonResponse::respondSuccess($address);
    }
}
