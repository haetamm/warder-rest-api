<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Response\ByDomainResponse;
use App\Http\Response\JsonResponse;
use App\Http\Response\PaginationResponse;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    protected $user;
    protected $seller;

    public function __construct()
    {
        $excludedFunctions = ['index', 'getByDomainSeller'];
        $currentFunction = request()->route()->getActionMethod();

        if (!in_array($currentFunction, $excludedFunctions)) {
            $this->user = User::find(Auth::id());
            $this->seller = $this->user->sellers()->first();
        }
    }

    private function applyRangeFilter($query, string $column, $value)
    {
        if (is_array($value)) {
            if (isset($value['min'])) {
                $query->where($column, '>=', $value['min']);
            }
            if (isset($value['max'])) {
                $query->where($column, '<=', $value['max']);
            }
        }
    }

    private function applyProductFilters($query, $extraFilters = [])
    {
        $defaultFilters = [
            'name',
            'condition',
            AllowedFilter::callback('price', fn($query, $value) =>
                $this->applyRangeFilter($query, 'price', $value)
            ),
            AllowedFilter::callback('stock', fn($query, $value) =>
                $this->applyRangeFilter($query, 'stock', $value)
            ),
            'sku',
        ];

        return $query->allowedFilters(array_merge($defaultFilters, $extraFilters))
                    ->allowedSorts(['name', 'price', 'created_at']);
    }

    public function index()
    {
        try {
            $query = QueryBuilder::for(Product::class)
                ->with('seller')
                ->active();

            if (request()->query('view') === 'true') {
                $query->orderBy('view', 'desc');
            }

            $products = $this->applyProductFilters($query)
                ->paginate(request()->input('per_page', 10));

            $formattedProducts = $products->map(function ($product) {
                return array_merge(
                    ByDomainResponse::formatProduct($product),
                    [
                        'seller' => ByDomainResponse::formatSeller($product->seller)
                    ]
                );
            });

            return JsonResponse::respondSuccess([
                'products' => $formattedProducts,
                'pagination' => PaginationResponse::formatPagination($products),
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to fetch products: ' . $e->getMessage(), 500);
        }
    }

    public function getByCurrentSeller()
    {
        try {
            $products = $this->applyProductFilters(QueryBuilder::for($this->seller->products()))
                ->paginate(request()->input('per_page', 10));

            $formattedProducts = $products->map(fn($product) => ByDomainResponse::formatProduct($product));
            return JsonResponse::respondSuccess([
                'products' => $formattedProducts,
                'pagination' => PaginationResponse::formatPagination($products),
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to fetch products: ' . $e->getMessage(), 500);
        }
    }

    public function getByDomainSeller($domain)
    {
        try {
            $seller = Seller::where('shop_domain', $domain)->first();

            if (!$seller) {
                return JsonResponse::respondFail('Seller not found', 404);
            }

            $products = $this->applyProductFilters(
                QueryBuilder::for(Product::class)
                    ->active()
                    ->where('seller_id', $seller->id),
                [AllowedFilter::exact('seller_id')]
            )->paginate(request()->input('per_page', 10));

            $formattedSeller = ByDomainResponse::formatSeller($seller);
            $formattedProducts = $products->map(fn($product) => ByDomainResponse::formatProduct($product));

            return JsonResponse::respondSuccess([
                'seller' => $formattedSeller,
                'products' => $formattedProducts,
                'pagination' => PaginationResponse::formatPagination($products),
            ]);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to fetch products: ' . $e->getMessage(), 500);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['seller_id'] = $this->seller->id;
            $data['is_active'] = null;

            $product = $this->seller->products()->create($data);
            return JsonResponse::respondSuccess($product);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Registration Product failed: ' . $e->getMessage(), 500);
        }
    }

    public function updateById(UpdateProductRequest $request, $id)
    {
        try {
            $product = $this->seller->products()->findOrFail($id);
            $product->update($request->validated());
            return JsonResponse::respondSuccess($product);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to update product: ' . $e->getMessage(), 500);
        }
    }

    public function updateStatusProductById($id)
    {
        try {
            $product = $this->seller->products()->findOrFail($id);
            $product->is_active = is_null($product->is_active) ? now() : null;
            $product->save();

            $message = is_null($product->is_active) ?
                'Product status set to active successfully' :
                'Product status set to inactive successfully';

            return JsonResponse::respondSuccess($product->fresh(), $message);
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to toggle product status: ' . $e->getMessage(), 500);
        }
    }

    public function deleteById($id)
    {
        try {
            $product = $this->seller->products()->findOrFail($id);
            $product->delete();
            return JsonResponse::respondSuccess('Product deleted successfully');
        } catch (\Exception $e) {
            return JsonResponse::respondFail('Failed to delete product: ' . $e->getMessage(), 500);
        }
    }
}
