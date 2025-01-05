<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Response\JsonResponse;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    public function index()
    {
        $addresses = Address::where('user_id', $this->user->id)->get();
        return JsonResponse::respondSuccess($addresses);
    }

    public function store(AddressRequest $request)
    {
        $validated = $request->validated();

        if ($validated['selected']) {
            Address::where('user_id', $this->user->id)->update(['selected' => false]);
        }

        $address = $this->user->addresses()->create($validated);

        return JsonResponse::respondSuccess($address);
    }

    public function getById($id)
    {
        $address = Address::where('id', $id)->where('user_id', $this->user->id)->first();
        if (!$address) {
            return JsonResponse::respondErrorNotFound("Address not found");
        }
        return JsonResponse::respondSuccess($address);
    }

    public function update(UpdateAddressRequest $request, $id)
    {
        $validated = $request->validated();

        $address = Address::where('id', $id)->where('user_id', $this->user->id)->first();
        if (!$address) {
            return JsonResponse::respondErrorNotFound("Address not found");
        }

        if (isset($validated['selected']) && $validated['selected']) {
            Address::where('user_id', $this->user->id)->update(['selected' => false]);
        }

        $validated = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $address->update($validated);

        return JsonResponse::respondSuccess($address);
    }

    public function deleteById($id)
    {
        $address = Address::where('id', $id)->where('user_id', $this->user->id)->first();
        if (!$address) {
            return JsonResponse::respondErrorNotFound("Address not found");
        }

        $address->delete();
        return JsonResponse::respondSuccess('Your data has been deleted successfully.');
    }
}
