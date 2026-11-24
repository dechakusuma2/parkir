<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Menguji perilaku dasar halaman publik aplikasi. */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     * Memastikan halaman utama dapat dimuat dengan respons HTTP sukses.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
