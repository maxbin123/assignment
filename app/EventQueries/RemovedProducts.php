<?php

namespace App\EventQueries;

use App\StorableEvents\RemovedFromCart;
use Spatie\EventSourcing\EventHandlers\Projectors\EventQuery;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class RemovedProducts extends EventQuery
{

    private array $removed_products = [];

    public function __construct()
    {
        EloquentStoredEvent::query()
            ->whereEvent(RemovedFromCart::class)
            ->each(
                fn(EloquentStoredEvent $event) => $this->apply($event->toStoredEvent())
            );
    }

    protected function applyRemovedFromCart(RemovedFromCart $event)
    {
        if (isset($this->removed_products[$event->product->id])) {
            $this->removed_products[$event->product->id]++;
        } else {
            $this->removed_products[$event->product->id] = 1;
        }
    }

    public function getRemovedProducts()
    {
        return $this->removed_products;
    }

}
