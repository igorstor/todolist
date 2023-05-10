<?php

namespace App\Models\Builders;

use App\Collections\OrderingInputsCollection;
use App\Enums\TaskStatusEnum;
use App\ValueObjects\OrderingValueObject;
use App\ValueObjects\PriorityValueObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TaskBuilder extends Builder
{
    public function searchIfSet(?string $search = null): self
    {
        return $this->when($search, function (self $builder) use ($search) {
            $builder->where('title', 'like', '%' . $search . '%');
        });
    }

    public function filterByPriorityIfSet(?PriorityValueObject $priority = null): self
    {
        return $this->when($priority, function (self $builder) use ($priority) {
            return $builder->wherePriorityRange($priority->getFrom(), $priority->getTo());
        });
    }

    public function filterByStatusesIfSet(?array $status = null): self
    {
        return $this->when($status, function (self $builder) use ($status) {
            return $this->whereStatuses($status);
        });
    }

    public function orderIfSet(OrderingInputsCollection $sortOrder)
    {
        return $this->when($sortOrder->isNotEmpty(), function (self $builder) use ($sortOrder) {
             $sortOrder->each(function (OrderingValueObject $order) use (&$builder) {
                return $builder->orderBy($order->getColumn(), $order->getDirection());
            });

            return $builder;
        });
    }

    public function whereStatuses(array $statuses): self
    {
        return $this->whereIn('status', $statuses);
    }

    public function done(): self
    {
        return $this->whereStatus(TaskStatusEnum::done());
    }

    public function todo(): self
    {
        return $this->whereStatus(TaskStatusEnum::todo());
    }


    public function whereStatus($status): self
    {
        return $this->where('status', $status);
    }

    public function wherePriorityRange(int $from, int $to): self
    {
        return $this->whereBetween('priority', [$from, $to]);
    }
}
