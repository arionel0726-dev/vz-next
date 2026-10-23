<?php

namespace App\Listeners;

use App\Cart\CartService;
use Illuminate\Auth\Events\Login;

/**
 * The cart lives entirely in the session (see App\Cart\CartService),
 * and Laravel's session regenerate on login (triggered internally by
 * Auth::login()) carries the existing session payload — including the
 * cart — forward under the new session ID. So the guest cart the user
 * built up is already "the" cart once they're authenticated; there is
 * no separate per-user cart to merge it into.
 *
 * This listener makes that behavior explicit and gives the cart one
 * guaranteed reconciliation pass right at the guest → authenticated
 * transition: CartService::items() silently drops any line whose
 * product/variant has since been unpublished or deleted, so the
 * authenticated session starts from a clean cart rather than waiting
 * for the next time the cart happens to be read.
 */
class MergeGuestCartOnLogin
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function handle(Login $event): void
    {
        $this->cart->items();
    }
}
