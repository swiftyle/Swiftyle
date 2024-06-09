<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Extend the correct TestCase

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_categories()
    {
        $categories = Category::factory()->count(5)->create();

        $this->assertCount(5, $categories);
        $this->assertDatabaseCount('categories', 5);
    }
}
