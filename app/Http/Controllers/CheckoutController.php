<?php

namespace App\Http\Controllers;

use App\Cart\CartLineItem;
use App\Cart\CartService;
use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Resources\CartItemResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\Translation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(Request $request): Response|RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect('/cart');
        }

        $user = $request->user();

        return Inertia::render('Checkout/Index', [
            'items' => CartItemResource::collection($items)->resolve(),
            'totals' => $this->cart->totals(),
            'contact' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect('/cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $data = $request->validated();
        $user = $request->user();

        $order = DB::transaction(function () use ($items, $data, $user) {
            $this->assertStockStillAvailable($items);

            $subtotal = round($items->sum(fn (CartLineItem $item) => $item->total()), 2);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'contact_name' => $data['contact_name'],
                'contact_email' => $data['contact_email'],
                'contact_phone' => $data['contact_phone'],
                'delivery_address' => $data['delivery_address'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product->id,
                    'variant_id' => $item->variant?->id,
                    'product_name' => Translation::pick($item->product->title),
                    'product_sku' => $item->variant?->sku ?? $item->product->sku,
                    'unit_price' => $item->unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->total(),
                ]);

                if ($item->variant !== null) {
                    ProductVariant::whereKey($item->variant->id)->decrement('stock', $item->quantity);
                } else {
                    Product::whereKey($item->product->id)->decrement('stock', $item->quantity);
                }
            }

            return $order;
        });

        $this->cart->clear();

        return redirect("/checkout/{$order->id}");
    }

    public function show(Request $request, Order $order): Response
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        $order->load('items');

        return Inertia::render('Checkout/Confirmation', [
            'order' => [
                'orderNumber' => $order->order_number,
                'status' => $order->status,
                'subtotal' => (float) $order->subtotal,
                'total' => (float) $order->total,
                'contactName' => $order->contact_name,
                'contactEmail' => $order->contact_email,
                'contactPhone' => $order->contact_phone,
                'deliveryAddress' => $order->delivery_address,
                'notes' => $order->notes,
                'createdAt' => $order->created_at->toIso8601String(),
                'items' => $order->items->map(fn ($item) => [
                    'productName' => $item->product_name,
                    'productSku' => $item->product_sku,
                    'unitPrice' => (float) $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => (float) $item->subtotal,
                ]),
            ],
        ]);
    }

    /**
     * Re-check stock against the current, locked state of each
     * product/variant row — the cart's own check happens at
     * add-to-cart time and can be stale by the time checkout runs.
     *
     * @param  Collection<int, CartLineItem>  $items
     */
    private function assertStockStillAvailable(Collection $items): void
    {
        foreach ($items as $item) {
            if ($item->variant !== null) {
                $locked = ProductVariant::whereKey($item->variant->id)->lockForUpdate()->first();
                $sku = $locked?->sku ?? $item->variant->sku;
            } else {
                $locked = Product::whereKey($item->product->id)->lockForUpdate()->first();
                $sku = $locked?->sku ?? $item->product->sku;
            }

            if (! $locked || $item->quantity > (int) $locked->stock) {
                throw ValidationException::withMessages([
                    'cart' => "Not enough stock available for {$sku}.",
                ]);
            }
        }
    }
}
