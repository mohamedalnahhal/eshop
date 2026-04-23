<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerRegisterController extends Controller
{
    protected string $guard = 'customer';
    protected string $redirectTo = '/';
    protected string $registerView = 'storefront.auth.signup';

    public function create(): View
    {
        return view($this->registerView);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $exists = Customer::where('tenant_id', tenant()->id)
            ->where('email', $request->string('email')->lower()->value())
            ->exists();

        if ($exists) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['email' => __('An account with this email already exists.')]);
        }

        $customer = Customer::create([
            'name' => $request->string('name')->value(),
            'email' => $request->string('email')->lower()->value(),
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::guard($this->guard)->login($customer);

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo);
    }
}