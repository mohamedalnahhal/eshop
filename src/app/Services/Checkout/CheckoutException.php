<?php

namespace App\Services\Checkout;

use RuntimeException;

class CheckoutException extends RuntimeException
{
    public static function emptyCart(): self
    {
        return new self('Cannot checkout with an empty cart.');
    }

    public static function outOfStock(string $productName, int $available): self
    {
        return new self(
            "\"{$productName}\" only has {$available} unit(s) left in stock."
        );
    }

    public static function invalidShippingMethod(): self
    {
        return new self('The selected shipping method is no longer available for your address. Please select another.');
    }

    public static function costMismatch(): self
    {
        return new self('Order cost changed. Please review your order before placing it.');
    }

    public static function totalMismatch(): self
    {
        return new self('Order total changed. Please review your order before placing it.');
    }

    public static function noCart(): self
    {
        return new self('No active cart found. Please add items before checking out.');
    }
}