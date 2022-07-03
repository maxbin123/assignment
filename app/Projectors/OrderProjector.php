<?php

namespace App\Projectors;

use App\Models\Order;
use App\StorableEvents\OrderCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class OrderProjector extends Projector
{
    public function onOrderCreated(OrderCreated $event)
    {
        $order = Order::create([
            'name' => $event->customer->name,
            'email' => $event->customer->email,
            'address' => $event->customer->address,
            'phone' => $event->customer->phone
        ]);

        foreach ($event->products as $product_id => $quantity) {
            $order->products()->attach($product_id, [
                'quantity' => $quantity,
            ]);
        }
    }
}
