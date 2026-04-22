<?php

namespace App\Enums;

enum PaymentProvider: string
{
    // case STRIPE = 'stripe';
    // case PAYPAL = 'paypal';
    case MOCK = 'mock';
    case MOCK_EXPRESS = 'mock_express';
    
    public function label(): string
    {
        return match($this) {
            // self::STRIPE => 'Stripe',
            // self::PAYPAL => 'PayPal',
            self::MOCK => 'Mock Sandbox',
            self::MOCK_EXPRESS => 'Express Mock Sandbox',
        };
    }
}