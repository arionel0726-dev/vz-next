<?php

namespace App\Http\Controllers;

use App\Cart\CartService;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Cart/Index', [
            'items' => CartItemResource::collection($this->cart->items())->resolve(),
            'totals' => $this->cart->totals(),
        ]);
    }

    public function store(AddCartItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $product = Product::findOrFail($data['product_id']);
        $variant = isset($data['variant_id'])
            ? ProductVariant::findOrFail($data['variant_id'])
            : null;

        $this->cart->add($product, $variant, (int) $data['quantity']);

        return back();
    }

    public function update(UpdateCartItemRequest $request, string $key): RedirectResponse
    {
        $this->cart->update($key, (int) $request->validated('quantity'));

        return back();
    }

    public function destroy(string $key): RedirectResponse
    {
        $this->cart->remove($key);

        return back();
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back();
    }
}
