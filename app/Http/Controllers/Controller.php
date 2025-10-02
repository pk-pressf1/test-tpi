<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Ticket Booking Thin Client API",
 *     version="1.0.0",
 *     description="REST API тонкого клиента для взаимодействия с внешним билетным шлюзом. Все данные проксируются от поставщика. База данных не используется."
 * )
 *
 * @OA\Server(url="http://localhost:8000/api/v1", description="Локальная разработка")
 * @OA\Server(url="https://yourdomain.com/api/v1", description="Продакшн")
 *
 * @OA\Schema(
 *     schema="Show",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Большой концерт")
 * )
 *
 * @OA\Schema(
 *     schema="Event",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=101),
 *     @OA\Property(property="show_id", type="integer", example=1),
 *     @OA\Property(property="date_time", type="string", format="date-time", example="2025-06-15T19:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Seat",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=501),
 *     @OA\Property(property="row", type="string", example="A"),
 *     @OA\Property(property="number", type="string", example="12"),
 *     @OA\Property(property="is_available", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="BookingRequest",
 *     type="object",
 *     required={"seat_ids", "customer_name"},
 *     @OA\Property(
 *         property="seat_ids",
 *         type="array",
 *         @OA\Items(type="integer", example=501)
 *     ),
 *     @OA\Property(property="customer_name", type="string", example="Иван Петров")
 * )
 *
 * @OA\Schema(
 *     schema="BookingResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="booking_id", type="string", example="BOOK-12345"),
 *     @OA\Property(property="message", type="string", example="Места успешно забронированы")
 * )
 */
abstract class Controller
{
    //
}
