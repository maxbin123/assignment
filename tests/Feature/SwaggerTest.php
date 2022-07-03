<?php

namespace Tests\Feature;

use Tests\TestCase;

class SwaggerTest extends TestCase
{
    public function test_documentation_is_generated(): void
    {
        $this->artisan('l5-swagger:generate')->assertExitCode(0);

        $this->assertFileExists(storage_path('api-docs/api-docs.json'));
        $api_docs = json_decode(file_get_contents(storage_path('api-docs/api-docs.json')), true);
        $this->assertArrayHasKey('info', $api_docs);
        $this->assertArrayHasKey('/api/cart/{uuid}', $api_docs['paths']);
    }
}
