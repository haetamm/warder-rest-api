<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Response\JsonResponse;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $user;
    protected $seller;

    public function __construct()
    {
        $this->user = User::find(Auth::id());
        $this->seller = $this->user->sellers()->first();
    }

    public function  store(ProductRequest $request)
    {
        $validated = $request->validated();
        try {
            $validated['seller_id'] = $this->seller->id;
            $product = $this->seller->products()->create($validated);
            return JsonResponse::respondSuccess($product);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Registration Product failed: ' . $e->getMessage(), 500);
        }
    }

    public function updateById(ProductRequest $request, $id)
    {
        $validated = $request->validated();

        $product = $this->seller->products()->find($id);
        if (!$product) {
            return JsonResponse::respondErrorNotFound('Product not found');
        }

        try {
            $product->update($validated);
            return JsonResponse::respondSuccess($product);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to update product: ' . $e->getMessage(), 500);
        }
    }

    public function getByCurrentSeller()
    {
        try {
            $products = $this->seller->products()->get();
            if ($products->isEmpty()) {
                return JsonResponse::respondSuccess([]);
            }

            return JsonResponse::respondSuccess($products);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to fetch products: ' . $e->getMessage(), 500);
        }
    }

    public function getByDomainSeller($domain)
    {
        try {
            $seller = Seller::where('shop_domain', $domain)->first();
            if (!$seller) {
                return JsonResponse::respondErrorNotFound('Seller not found');
            }

            $products = $seller->products;
            if ($products->isEmpty()) {
                return JsonResponse::respondSuccess([]);
            }

            return JsonResponse::respondSuccess($products);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to fetch products: ' . $e->getMessage(), 500);
        }
    }

    public function deleteById($id)
    {
        try {
            $product = $this->seller->products()->find($id);
            if (!$product) {
                return JsonResponse::respondErrorNotFound('Product not found');
            }
            $product->delete();

            return JsonResponse::respondSuccess('Product deleted successfully');
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to delete product: ' . $e->getMessage(), 500);
        }
    }
}
