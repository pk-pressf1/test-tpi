<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShowsTest extends TestCase
{
    public function test_can_get_shows_list()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/shows' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Concert A'
                ],
                [
                    'id' => 2,
                    'name' => 'Theater B'
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/v1/shows');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'Concert A']);
    }
}
