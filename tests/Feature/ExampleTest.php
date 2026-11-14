<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_booking_page(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/book');
        $this->assertEquals(200, $response->status());
    }
}
