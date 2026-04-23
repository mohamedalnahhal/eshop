<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerLoginController extends Controller
{
    protected string $guard = 'customer';
    protected string $redirectTo = '/';
    protected string $loginView = 'storefront.auth.login';

    public function create(): View
    {
        return view($this->loginView);
    }

    public function store(Request $request, CartService $cartService): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $guard = Auth::guard($this->guard);

        if (!$guard->attempt([
            'email' => $request->string('email')->lower()->value(),
            'password' => $request->input('password')
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $cartService->mergeGuestIntoCustomer($request->session());

        return redirect()->intended($this->redirectTo);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard($this->guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}