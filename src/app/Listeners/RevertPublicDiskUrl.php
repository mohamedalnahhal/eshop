<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Events\RevertedToCentralContext;

class RevertPublicDiskUrl
{
    public function handle(RevertedToCentralContext $event): void
    {
        // revert FixTenantPublicDiskUrl when returning to central domain
        config(['filesystems.disks.public.url' => config('app.url') . '/storage']);
        Storage::forgetDisk('public');
    }
}
