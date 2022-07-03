<?php

namespace App\StorableEvents;

use App\DataTransferObjects\CustomerObject;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderCreated extends ShouldBeStored
{
    public CustomerObject $customer;
    public array $products;

    public function __construct(CustomerObject $customer, array $products)
    {
        $this->customer = $customer;
        $this->products = $products;
    }
}
