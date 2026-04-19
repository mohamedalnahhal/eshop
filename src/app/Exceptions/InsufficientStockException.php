<?php

namespace App\Exceptions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public function __construct(int $available)
    {
        $message = $available > 0
            ? __('Stock exhausted! Only :count item(s) left in stock.', ['count' => $available])
            : __('This product is out of stock.');

        parent::__construct($message);
    }
}
