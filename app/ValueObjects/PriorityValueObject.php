<?php

namespace App\ValueObjects;

class PriorityValueObject
{
    public function __construct(protected int $from, protected int $to)
    {
        $this->flipValuesIfNeeded($from, $to);
    }

    public static function make(?int $from, ?int $to)
    {
        return new self($from ?? 1, $to ?? 1);
    }

    protected function flipValuesIfNeeded(int $from, int $to): void
    {
        if ($from > $to) {
            $smallerValue = $to;

            $this->to   = $from;
            $this->from = $smallerValue;
        }
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): int
    {
        return $this->to;
    }

}
