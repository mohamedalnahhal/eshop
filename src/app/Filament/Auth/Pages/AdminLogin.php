<?php

namespace App\Filament\Auth\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class AdminLogin extends BaseLogin
{
    public function getHeading(): string | Htmlable
    {
        return new HtmlString('<div>' . parent::getHeading() . '<br><span class="font-normal text-gray-700 text-base">as</span> <span class="font-semibold text-base text-primary-700">System Admin</span></div>');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Secure Login'); 
    }
}