<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(): Response
    {
        $products = Product::query()
            ->with(['brand', 'category', 'images', 'variants'])
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return Inertia::render('Catalog/Index', [
            'products' => $products
                ->map(fn (Product $product) => (new ProductResource($product))->resolve())
                ->values()
                ->all(),
        ]);
    }
}
