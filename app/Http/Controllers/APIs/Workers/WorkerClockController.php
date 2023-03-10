<?php

namespace App\Http\Controllers\APIs\Workers;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\Workers\ClockInsRequest;
use App\Http\Requests\APIs\Workers\ClockInWorkerRequest;
use App\Models\User;
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
     * store worker clock in
     * @param ClockInWorkerRequest $clockInWorkerRequest
     * @return JsonResponse
     * @throws ValidationException
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
