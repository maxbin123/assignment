<?php

namespace Tests;

use App\DataTransferObjects\CustomerObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use WithFaker;

    public function getCustomer()
    {
        return new CustomerObject(
            name: $this->faker->name(),
            email: $this->faker->email(),
            address: $this->faker->streetAddress(),
            phone: $this->faker->phoneNumber(),
        );
    }
}
