<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductDetailResource;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(string $slug): Response
    {
        $product = Product::query()
            ->where('is_published', true)
            // slug is a locale-keyed JSON column ({"ro": "...", "ru": "..."}).
            // Narrow with a LIKE on the raw JSON text first, then confirm
            // an exact match against the decoded values — avoids relying
            // on JSON_CONTAINS' unreliable behavior for object values.
            ->where('slug', 'like', '%"'.$slug.'"%')
            ->with([
                'brand',
                'category',
                'images',
                'variants.images',
                'featureValues.feature',
                'relatedProducts.brand',
                'relatedProducts.category',
                'relatedProducts.images',
            ])
            ->get()
            ->first(fn (Product $candidate) => in_array(
                $slug,
                array_values($candidate->slug ?? []),
                true
            ));

        if (! $product) {
            abort(404);
        }

        return Inertia::render('Product/Show', [
            'product' => (new ProductDetailResource($product))->resolve(),
        ]);
    }
}
