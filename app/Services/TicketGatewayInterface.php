<?php

namespace App\Services;

interface TicketGatewayInterface
{
    public function getShows(): array;
    public function getShowEvents(int $showId): array;
    public function getEventSeats(int $eventId): array;
    public function bookSeats(int $eventId, array $seatIds, string $customerName): array;
}
