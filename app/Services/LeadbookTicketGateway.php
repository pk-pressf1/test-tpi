<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class LeadbookTicketGateway implements TicketGatewayInterface
{
    private string $baseUrl = 'https://leadbook.ru/test-task-api';
    private string $token;

    public function __construct()
    {
        $this->token = config('services.leadbook.token');
    }

    private function client()
    {
        return Http::withToken($this->token)
            ->baseUrl($this->baseUrl)
            ->timeout(30)
            ->throw(); // выбрасывает исключение при 4xx/5xx
    }

    public function getShows(): array
    {

        return $this->client()->get('/shows')->json();
    }

    public function getShowEvents(int $showId): array
    {
        return $this->client()->get("/shows/{$showId}/events")->json();
    }

    public function getEventSeats(int $eventId): array
    {
        return $this->client()->get("/events/{$eventId}/seats")->json();
    }

    public function bookSeats(int $eventId, array $seatIds, string $customerName): array
    {
        return $this->client()->post("/events/{$eventId}/book", [
            'seat_ids' => $seatIds,
            'customer_name' => $customerName,
        ])->json();
    }
}
