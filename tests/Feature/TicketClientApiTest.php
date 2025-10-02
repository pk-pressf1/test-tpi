<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TicketClientApiTest extends TestCase
{
    public function test_can_get_list_of_shows()
    {
        // Мокаем ответ внешнего шлюза
        Http::fake([
            'https://leadbook.ru/test-task-api/shows' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Рок-концерт',
                ],
                [
                    'id' => 2,
                    'name' => 'Балет «Лебединое озеро»',
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/v1/shows');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'Рок-концерт']);
    }

    public function test_can_get_events_for_show()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/shows/5/events' => Http::response([
                [
                    'id' => 101,
                    'show_id' => 5,
                    'date_time' => '2025-06-20T19:00:00Z',
                ],
                [
                    'id' => 102,
                    'show_id' => 5,
                    'date_time' => '2025-06-21T19:00:00Z',
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/v1/shows/5');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['id' => 101]);
    }

    public function test_can_get_seats_for_event()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/events/101/seats' => Http::response([
                [
                    'id' => 501,
                    'row' => 'A',
                    'number' => '1',
                    'is_available' => true,
                ],
                [
                    'id' => 502,
                    'row' => 'A',
                    'number' => '2',
                    'is_available' => false,
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/v1/events/101/seats');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['row' => 'A', 'number' => '1', 'is_available' => true]);
    }

    public function test_can_book_seats_for_event()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/events/101/book' => Http::response([
                'success' => true,
                'booking_id' => 'BOOK-7890',
                'message' => 'Места успешно забронированы',
            ], 201),
        ]);

        $payload = [
            'seat_ids' => [501, 503],
            'customer_name' => 'Анна Иванова',
        ];

        $response = $this->postJson('/api/v1/events/101/book', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'booking_id' => 'BOOK-7890',
            ]);

        // Проверяем, что внешний API получил правильный запрос
        Http::assertSent(function ($request) use ($payload) {
            return $request->url() === 'https://leadbook.ru/test-task-api/events/101/book'
                && $request->data() === $payload;
        });
    }

    public function test_booking_fails_with_validation_error_when_missing_customer_name()
    {
        $response = $this->postJson('/api/v1/events/101/book', [
            'seat_ids' => [501],
            // customer_name отсутствует
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_name']);
    }

    public function test_booking_fails_with_validation_error_when_seat_ids_empty()
    {
        $response = $this->postJson('/api/v1/events/101/book', [
            'seat_ids' => [],
            'customer_name' => 'Иван Петров',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['seat_ids']);
    }
}
