<?php

namespace App\Jobs;

use Stancl\Tenancy\Contracts\Tenant;

class CreateFrameworkDirectoriesForTenant
{
    protected $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {
        $this->tenant->run(function ($tenant) {
            mkdir(storage_path('framework/cache'), 0777, true);
            mkdir(storage_path('framework/sessions'), 0777, true);
            mkdir(storage_path('framework/views'), 0777, true);
        });
    }
}