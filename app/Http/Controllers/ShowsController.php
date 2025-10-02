<?php

namespace App\Http\Controllers;

use App\Services\TicketGatewayInterface;
use Illuminate\Http\JsonResponse;


class ShowsController extends Controller
{
    public function __construct(protected TicketGatewayInterface $gateway)
    {}


    /**
     * @OA\Get(
     *     path="/api/v1/shows",
     *     tags={"Shows"},
     *     summary="Получить список мероприятий",
     *     description="Возвращает список всех доступных мероприятий от билетного шлюза.",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Show")
     *         )
     *     ),
     *     @OA\Response(response=502, description="Ошибка при обращении к внешнему шлюзу")
     * )
     */
    public function index(): JsonResponse
    {
        $shows = $this->gateway->getShows();
        return response()->json($shows);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/shows/{id}",
     *     tags={"Shows"},
     *     summary="Получить события мероприятия",
     *     description="Возвращает список событий (сеансов) для указанного мероприятия.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID мероприятия",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Event")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Мероприятие не найдено"),
     *     @OA\Response(response=502, description="Ошибка шлюза")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $events = $this->gateway->getShowEvents($id);
        return response()->json($events);
    }
}
