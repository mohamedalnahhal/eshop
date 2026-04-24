<?php

namespace App\Filament\Auth\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Actions\Action;

class AdminLogin extends BaseLogin
{

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Secure Login'); 
    }
}