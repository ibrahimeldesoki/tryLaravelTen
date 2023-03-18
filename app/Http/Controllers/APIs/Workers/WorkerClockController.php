<?php

namespace App\Http\Controllers\APIs\Workers;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\Workers\ClockInsRequest;
use App\Http\Requests\APIs\Workers\ClockInWorkerRequest;
use App\Models\WorkerClock;
use App\Utilities\DistanceUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class WorkerClockController extends Controller
{

    /**
     * @param WorkerClock $workerClock
     */
    public function __construct(private WorkerClock $workerClock)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/clock-in",
     *     summary="Store a new clock-in for a worker.",
     *     description="Store a new clock-in record for a worker based on the provided data in the request body.",
     *     operationId="storeClockInWorker",
     *     tags={"Clock-In"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="The clock-in worker request body.",
     *         @OA\JsonContent(ref="#/components/schemas/ClockInWorkerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success message",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="success Clock in")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="outside permitted area")
     *         )
     *     )
     * )
     */
    public function store(ClockInWorkerRequest $clockInWorkerRequest): JsonResponse
    {
        $distance = new DistanceUtil();
        $distance = $distance->getDistance($clockInWorkerRequest->latitude, $clockInWorkerRequest->longitude);
        if ($distance > 2.0) {
            throw  ValidationException::withMessages(['outside permitted area']);
        }

        $attributes = $clockInWorkerRequest->all();
        $attributes['type'] = $this->workerClock::TYPE_IN;
        $this->workerClock->create($attributes);

        return response()->json([
            'message' => 'success Clock in',
        ], 200);
    }

    /**
         * @OA\Get(
     *     path="/worker/clock-ins",
     *     summary="Get clock ins by worker ID",
     *     description="Get a list of clock ins for a worker by their ID",
     *     tags={"Worker Clock Ins"},
     *     @OA\Parameter(
     *         name="worker_id",
     *         in="query",
     *         description="ID of the worker",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="clocks",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="worker_id",
     *                         type="integer"
     *                     ),
     *                      @OA\Property(
     *                         property="time",
     *                         type="integer",
     *                         description="UNIX timestamp of the clock-in time",
     *                         example=1647075063
     *                     ),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object"
     *             )
     *         )
     *     ),
     *
     *       @OA\Response(
     *          response="401",
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated.",
     *              ),
     *          ),
     *      )
     * )
     */

    public function index(ClockInsRequest $clockInsRequest): JsonResponse
    {
        $clockIns = $this->workerClock
            ->select('id', 'worker_id', 'time')
            ->with('user:id,name')
            ->where('worker_id', $clockInsRequest->worker_id)
            ->paginate(20);

        return response()->json([
            'clocks' => $clockIns,
        ], 200);
    }
}
