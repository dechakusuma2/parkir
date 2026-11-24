<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/** Menguji perilaku dasar tanpa bootstrap aplikasi Laravel. */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     * Memastikan pernyataan dasar PHPUnit dapat dijalankan pada suite unit.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
