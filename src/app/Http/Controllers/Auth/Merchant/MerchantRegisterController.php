<?php

namespace App\Http\Controllers\Auth\Merchant;

use App\Enums\TenantUserRole;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Stancl\Tenancy\Database\Models\Domain;

class MerchantRegisterController extends Controller
{
    public function create(Request $request)
    {
        $isAr = $request->cookie('locale', config('app.locale', 'ar')) === 'ar';
        return view('auth.register', compact('isAr'));
    }

    public function store(Request $request)
    {
        $centralDomain = config('tenancy.central_domains')[0];

        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'confirmed', Password::min(8)],
            'shop_name' => ['required', 'string', 'max:100'],
            'subdomain' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/',
                function ($attribute, $value, $fail) use ($centralDomain) {
                    $full = $value . '.' . $centralDomain;
                    if (Domain::where('domain', $full)->exists()) {
                        $fail(__('This subdomain is already taken.'));
                    }
                },
            ],
        ]);

        DB::transaction(function () use ($request, $centralDomain) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => UserRole::TENANT,
            ]);

            $tenant = Tenant::create([
                'name'     => $request->shop_name,
                'owner_id' => $user->id,
                'status'   => \App\Enums\TenantStatus::PENDING,
            ]);

            $tenant->domains()->create([
                'domain' => $request->subdomain . '.' . $centralDomain,
            ]);

            $tenant->users()->attach($user->id, [
                'role' => TenantUserRole::OWNER,
            ]);

            Auth::login($user);
        });

        $subdomain = $request->subdomain;
        $centralDomain = config('tenancy.central_domains')[0];
        $tenantUrl = 'http://' . $subdomain . '.' . $centralDomain . ':' . env('APP_PORT') . '/admin';

        return redirect($tenantUrl)
            ->with('success', 'Your store was created! Welcome aboard.');
    }
}
