<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class CustomerObject extends DataTransferObject
{
    public ?string $name;
    public ?string $email;
    public ?string $address;
    public ?string $phone;
}
