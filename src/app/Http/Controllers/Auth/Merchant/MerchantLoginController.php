<?php

namespace App\Http\Controllers\Auth\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantLoginController extends Controller
{
    public function create(Request $request)
    {
        $isAr = $request->cookie('locale', config('app.locale', 'ar')) === 'ar';
        return view('auth.login', compact('isAr'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('These credentials do not match our records.'),
            ])->onlyInput('email');
        }

        $user = Auth::user();

        $tenant = $user->ownedTenants()->first()
            ?? $user->tenants()->first();

        if (! $tenant) {
            Auth::logout();
            return back()->withErrors([
                'email' => __('No store found for this account.'),
            ])->onlyInput('email');
        }

        $domain = $tenant->domains()->first();

        if (! $domain) {
            Auth::logout();
            return back()->withErrors([
                'email' => __('Your store domain is not configured yet.'),
            ])->onlyInput('email');
        }

        return redirect('http://' . $domain->domain . ':' . env('APP_PORT') . '/admin');
    }
}
