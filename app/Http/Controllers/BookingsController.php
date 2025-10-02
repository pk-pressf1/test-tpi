<?php

namespace App\Http\Controllers;

use App\Services\TicketGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BookingsController extends Controller
{
    public function __construct(protected TicketGatewayInterface $gateway)
    {}


    /**
     * @OA\Post(
     *     path="/api/v1/events/{eventId}/book",
     *     tags={"Bookings"},
     *     summary="Забронировать места на событие",
     *     description="Бронирует указанные места для события. Требуется имя покупателя.",
     *     @OA\Parameter(
     *         name="eventId",
     *         in="path",
     *         required=true,
     *         description="ID события",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для бронирования",
     *         @OA\JsonContent(ref="#/components/schemas/BookingRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Места успешно забронированы",
     *         @OA\JsonContent(ref="#/components/schemas/BookingResponse")
     *     ),
     *     @OA\Response(response=400, description="Некорректные данные (например, недоступные места)"),
     *     @OA\Response(response=404, description="Событие не найдено"),
     *     @OA\Response(response=502, description="Ошибка шлюза")
     * )
     */
    public function store(Request $request, int $eventId): JsonResponse
    {
        $validated = $request->validate([
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer',
            'customer_name' => 'required|string|max:255',
        ]);

        $result = $this->gateway->bookSeats(
            $eventId,
            $validated['seat_ids'],
            $validated['customer_name']
        );

        return response()->json($result, 201);
    }
}
