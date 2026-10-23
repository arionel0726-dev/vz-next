<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Account/Index', [
            'user' => $request->user()->only(['id', 'name', 'email', 'phone']),
        ]);
    }

    /**
     * Order history placeholder — no order/checkout system exists
     * yet, this just reserves the page and the nav link for it.
     */
    public function orders(): Response
    {
        return Inertia::render('Account/Orders');
    }
}
