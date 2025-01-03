<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRegionSellerRequest;
use App\Http\Requests\RegisterSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Http\Response\JsonResponse;
use App\Models\Role;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    public function getCurrentSeller()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        return JsonResponse::respondSuccess($seller);
    }

    public function store(RegisterSellerRequest $request)
    {
        $validated = $request->validated();

        $seller = $this->user->sellers()->create([
            'shop_name' => $validated['shop_name'],
            'shop_domain' => $validated['shop_domain'],
            'user_id' => $this->user->id,
        ]);

        return JsonResponse::respondSuccess($seller);
    }

    public function storeRegion(RegisterRegionSellerRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $seller = $this->user->sellers;

            if (!$seller) {
                return JsonResponse::respondErrorNotFound('Shop name and shop domain have not been registered.');
            }

            $role = Role::where('name', 'SELLER')->first();
            if (!$role) {
                throw new \Exception('Role SELLER not found');
            }

            if (!$this->user->roles->pluck('name')->contains('SELLER')) {
                $this->user->roles()->attach($role);
            }

            $this->user->load('roles');

            if (!$seller->update($validated)) {
                throw new \Exception('Failed to update seller region.');
            }

            DB::commit();

            return JsonResponse::respondSuccess([
                'seller' => $seller,
                'roles' => $this->user->roles->pluck('name'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponse::respondFail('Registration seller failed: ' . $e->getMessage(), 500);
        }
    }

    public function updateSeller(UpdateSellerRequest $request)
    {

        $validatedData = $request->validated();

        // Filter nilai-nilai yang bukan null atau string kosong, kecuali slogan dan desc
        $validated = array_filter($validatedData, function ($value, $key) {
            if (in_array($key, ['slogan', 'desc'])) {
                return true; // Jangan filter untuk slogan dan desc
            }
            return $value !== null && $value !== ''; // Nilai lainnya harus tidak null atau string kosong
        }, ARRAY_FILTER_USE_BOTH);


        if ($this->user->sellers instanceof Seller) {
            $this->user->sellers->update($validated);
        }


        $reponse = $this->user->sellers;

        return JsonResponse::respondSuccess($reponse);
    }
}
