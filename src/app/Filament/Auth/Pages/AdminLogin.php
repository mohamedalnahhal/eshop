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
        return new HtmlString('<div class="mt-4">' . parent::getHeading() . '</div>');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Secure Login'); 
    }
}