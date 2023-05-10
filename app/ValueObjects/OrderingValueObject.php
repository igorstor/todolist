<?php

namespace App\ValueObjects;

use Illuminate\Support\Arr;

class OrderingValueObject
{
    public string $column;
    public string $direction = 'ask';

    public function __construct($sortOrder)
    {
        $this->column = Arr::get($sortOrder, 'column');
        $this->direction = Arr::get($sortOrder, 'direction');
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
