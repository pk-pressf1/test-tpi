<?php

namespace App\Http\Controllers;

use App\Services\TicketGatewayInterface;
use Illuminate\Http\JsonResponse;

class EventsController extends Controller
{
    public function __construct(protected TicketGatewayInterface $gateway)
    {}

    /**
     * @OA\Get(
     *     path="/api/v1/events/{id}/seats",
     *     tags={"Events"},
     *     summary="Получить схему зала (места) события",
     *     description="Возвращает список мест для указанного события с информацией о доступности.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID события",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Seat")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Событие не найдено"),
     *     @OA\Response(response=502, description="Ошибка шлюза")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $seats = $this->gateway->getEventSeats($id);

        return response()->json($seats);
    }
}
