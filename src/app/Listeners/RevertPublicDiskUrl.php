<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class RevertPublicDiskUrl
{
    public function handle(TenancyBootstrapped $event): void
    {
        // revert FixTenantPublicDiskUrl when returning to central domain
        config(['filesystems.disks.public.url' => config('app.url') . '/storage']);
        Storage::forgetDisk('public');
    }
}
