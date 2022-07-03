<?php

namespace App\StorableEvents;

use App\DataTransferObjects\CustomerObject;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderCreated extends ShouldBeStored
{
    public CustomerObject $customer;

    public function __construct(CustomerObject $customer)
    {
        $this->customer = $customer;
    }
}
