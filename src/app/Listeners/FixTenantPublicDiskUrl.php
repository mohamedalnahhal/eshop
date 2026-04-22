<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class FixTenantPublicDiskUrl
{
    public function handle(TenancyBootstrapped $event): void
    {
        // The FilesystemTenancyBootstrapper overrides disk roots but not URLs.
        // Without this, Storage::disk('public')->url() returns the central APP_URL/storage,
        // causing Filament FileUpload to fail when fetching file sizes for tenant files.
        config(['filesystems.disks.public.url' => rtrim(tenant_asset(''), '/')]);
        Storage::forgetDisk('public');
    }
}
