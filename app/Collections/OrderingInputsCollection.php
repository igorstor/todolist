<?php

namespace App\Collections;

use App\ValueObjects\OrderingValueObject;
use Illuminate\Support\Collection;

class OrderingInputsCollection extends Collection
{
    public static function make($items = [])
    {
        return parent::make($items)
                     ->unique('column')
                     ->mapInto(OrderingValueObject::class);
    }
}
