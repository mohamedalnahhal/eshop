<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Payable
{
    public function payments(): MorphMany;
}