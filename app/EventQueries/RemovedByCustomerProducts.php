<?php

namespace App\EventQueries;

use App\Models\Product;
use App\StorableEvents\OrderCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\EventQuery;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class RemovedByCustomerProducts extends EventQuery
{
    private array $removed_products = [];

    public function __construct()
    {
        EloquentStoredEvent::query()
            ->whereEvent(OrderCreated::class)
            ->each(
                fn(EloquentStoredEvent $event) => $this->apply($event->toStoredEvent())
            );
    }

    protected function applyOrderCreated(OrderCreated $event)
    {
        foreach ($event->products as $product) {
            $this->removed_products[] =
                [
                    'product' => Product::find($product),
                    'customer' => $event->customer
                ];
        }
    }

    public function getRemovedProducts(): array
    {
        return $this->removed_products;
    }
}
