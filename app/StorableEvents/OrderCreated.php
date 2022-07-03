<?php

namespace App\StorableEvents;

use App\DataTransferObjects\CustomerObject;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderCreated extends ShouldBeStored
{
    public CustomerObject $customer;
    public array $products;
    public array $removed_products;

    public function __construct(CustomerObject $customer, array $products, array $removed_products)
    {
        $this->customer = $customer;
        $this->products = $products;
        $this->removed_products = $removed_products;
    }
}
